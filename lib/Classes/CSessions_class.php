<?php
/**
 * Capital Property Management System
 *
 * File: CSessions_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CSession
 */
class CSession{
    private $loggedin = false;
    private $sesion_handle;

    public function __construct(){
        global $gx_db, $gx_config;

        session_start();

        $this->sesion_handle = new SecureSession();
        $this->sesion_handle->check_browser = true;
        $this->sesion_handle->check_ip_blocks = 4;
        $this->sesion_handle->secure_word = 'PEaNUtsAmDButter';//$gx_config->global_config['secureword'];
        $this->sesion_handle->regenerate_id = true;
        $this->sesion_handle->Open();

        if(!$this->CheckSession()){
            die('Error 12001: All your bases belong to us!');
        }
    }

    /**
     * @description returns if logged in.
     * @return bool
     */



    public function killsession(){
        session_destroy();
        if(isset($_SESSION)){
            unset($_SESSION);
        }
    }

    /**
    * @description Gets variable if it exists else returns false
    * @param $item
    * @return mixed (bool of no such value)
    */
    public function GetSessionItem($item){
        if(isset($_SESSION["CPMSYS"][$item])){
            return $_SESSION["CPMSYS"][$item];
        }
        return false;
    }

    /**
     * @description sets the item to $_SESSION
     * @param $item
     * @param $value
     * @return mixed
     */
    public function SetSessionItem($item, $value){
        return $_SESSION["CPMSYS"][$item] = $value;
    }

    public function DestroySessionItem($item){
        if(isset($_SESSION["CPMSYS"][$item])){
            unset($_SESSION["CPMSYS"][$item]);
            if(isset($_SESSION["CPMSYS"][$item])){
                return false;
            }
        }
        return true;
    }

      /**
     * @return string
     */
    private function getIp(){
        return gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }

    public function CheckSession(){
        return $this->sesion_handle->check();
    }


}



/*
  SecureSession class
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  Released under GNU Public License
*/
class SecureSession
{
    // Include browser name in fingerprint?
    public $check_browser = true;
    // How many numbers from IP use in fingerprint?
    public $check_ip_blocks = 0;
    // Control word - any word you want.
    public $secure_word = 'SECURESTAFF';
    // Regenerate session ID to prevent fixation attacks?
    public $regenerate_id = true;

    // Call this when init session.
    function Open()
    {
        $_SESSION['ss_fprint'] = $this->_Fingerprint();
        $this->_RegenerateId();
    }

    // Call this to check session.
    function Check()
    {
        $this->_RegenerateId();
        return (isset($_SESSION['ss_fprint']) && $_SESSION['ss_fprint'] == $this->_Fingerprint());
    }

    // Internal function. Returns MD5 from fingerprint.
    function _Fingerprint()
    {
        $fingerprint = $this->secure_word;
        if ($this->check_browser) {
            $fingerprint .= $_SERVER['HTTP_USER_AGENT'];
        }
        if ($this->check_ip_blocks) {
            $num_blocks = abs(intval($this->check_ip_blocks));
            if ($num_blocks > 4) {
                $num_blocks = 4;
            }
            $blocks = explode('.', $_SERVER['REMOTE_ADDR']);
            for ($i = 0; $i < $num_blocks; $i++) {
                $fingerprint .= $blocks[$i] . '.';
            }
        }
        return md5($fingerprint);
    }

    // Internal function. Regenerates session ID if possible.
    function _RegenerateId()
    {
        if ($this->regenerate_id && function_exists('session_regenerate_id')) {
            if (version_compare(phpversion(), '5.1.0', '>=')) {
                session_regenerate_id(true);
            } else {
                session_regenerate_id();
            }
        }
    }
}