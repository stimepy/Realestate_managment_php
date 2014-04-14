<?php
/**
 * Capital Property Management System
 *
 * File: CSessions_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014
 * Version 0.1
 * Modified: 4/13/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CSession
 */
class CSession{
    private $loggedin = false;
    public $user_info;

    public function __construct(){
      //  global $db,  $gx_config;
        session_start();
        //checking if user is logged in
       if (!isset($_SESSION["minibase"]["user"])){
            $this->getloggedin();
        }
        else{
            if(!isset($this->user_info)){
                $this->user_info = $_SESSION["minibase"]["raw"][0];
            }
            $this->loggedin = true;
        }
    }

    /**
     * @description returns if logged in.
     * @return bool
     */
    public function getLoginStatus(){
        return $this->loggedin;
    }

    private function getloggedin(){
        global $gx_config, $gx_db;
        $sub = GetVar('login', '');
        if ($sub == 'go'){ //Do some more here...
            $login_name = GetVar('user', '');
            //todo encerypt password.
            $password = GetVar('pass', '');
            //authentication
            $user = $gx_db->QuerySelectLimit($gx_config->config[tables][users],'*',"`user_login` = '{$login_name}' AND `user_password` = '{$password}'");

            if (isset($user)) {
                $_SESSION["minibase"]["user"] = 1;
                $_SESSION["minibase"]["raw"] =  $user;
                $this->user_info = $user;
                $this->loggedin = true;
                //redirecing to viuw sites
                header("Location: $gx_config->config[default_location]");
                exit;
            }
            else{
                $this->loggedin = false;
            }
        }
        else{
            $this->loggedin = false;
        }
    }


} 