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
    private $local_db_conn;
    public $user_info;
    private $session_cookie_lifetime = 0;
    private $session_cookie_path = '/';
    private $session_cookie_domain = '';
    private $session_cache_expire = 1410;
    private $session_lifetime = 1410;
    private $session_cookie_secure = false;
    private $session_use_only_cookies = true;
    private $session_read;

    public function __construct(){
        global $gx_db;
        IniSet("session.save_handler", "user" );

        session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
        session_name("CPMSSESSIONID");
        session_set_cookie_params($this->session_cookie_lifetime, $this->session_cookie_path, $this->session_cookie_domain, $this->session_cookie_secure);
        e107_ini_set ("session.use_only_cookies", $this->session_use_only_cookies );
        session_cache_expire ($this->session_cache_expire);
        IniSet("session.url_rewriter.tags", 'a=href,area=href,frame=src,input=src,form=fakeentry');

        if ($row =$gx_db->selectRow("session", "session_id", "session_ip='".$this->getIp())) {
            session_id($row['session_id']);
       }

        session_start();
        //checking if user is logged in
        $item = getSessionItem('user');
       if (!$item){
            $this->getloggedin();
        }
        else{
            if(!isset($this->user_info)){
                $this->user_info = $this->getSessionItem("raw");
                $this->user_info = $this->user_info[0];
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
                $this->getSessionItem("user", 1);
                $this->getSessionItem("raw", $user);
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

    /**
     * @description Gets variable if it exists else returns false
     * @param $item
     * @return mixed (bool of no such value)
     */
    public function getSessionItem($item){
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
    public function setSessionItem($item, $value){
        return $_SESSION["CPMSYS"][$item] = $value;
    }


    /**
     * @description part of session_set_save_handler
     * @param $save_path
     * @param $session_name
     * @return bool
     */
    function sess_open($save_path, $session_name) {
        global $gx_config;
        if (isset($gx_config->config["database"])) {
            $this->local_db_conn = new CDatabase($gx_config->config["database"]);
        }
        return true;
    }

    /**
     * @description part of session_set_save_handler
     * @return bool]
     */
    function sess_close() {
        $this->local_db_conn->close();
        return true;
    }

    /**
     * @description part of session_set_save_handler
     * @param $session_id
     * @return bool
     */
    function sess_read($session_id) {
        if ($session_read = $this->local_db_conn->selectRow("session", "*", "WHERE session_id = '{$session_id}' AND session_expire > " . time())) {
            return $session_read['session_data'];
        }
        else {
            return FALSE;
        }
    }

    /**
     * @description part of session_set_save_handler
     * @param $session_id
     * @param $session_data
     * @return bool
     */
    function sess_write($session_id, $session_data){
        if (!$session_data) {
            return FALSE;
        }
        $expiry = time() + $this->session_lifetime;
        if ($this->session_read && $this->session_read['session_ip'] != gethostbyaddr($_SERVER['REMOTE_ADDR']) ){
            session_destroy();
            die("Invalid session ID");
        }
        $_session_data = mysql_real_escape_string($session_data);
        if ($this->session_read) {
            $this->local_db_conn->UndefQuery("session", "session_expire = {$expiry}, session_data = '{$_session_data}' WHERE session_id = '{$session_id}' AND session_expire > ". time(), DBUPDATE);
        }
        else {
            $options = array($session_id, $expiry, time(), $this->getIp() , $_session_data);
            $this->local_db_conn->UndefQuery("session", $options, DBINSERT);
        }
        return TRUE;
    }

    /**
     * @description part of session_set_save_handler
     * @param $session_id
     * @return bool
     */
    function sess_destroy($session_id) {
        $this->local_db_conn->deleteQuery("session", "session_id = '$session_id'");
        return TRUE;
    }

    /**
     * @description part of session_set_save_handler
     * @param $session_lifetime
     * @return mixed
     */
    function sess_gc($session_lifetime) {
        $this->local_db_conn->deleteQuery("session", "session_expire < " . time());
        return $this->local_db_conn->AffectedRows();
    }

    /**
     * @return string
     */
    private function getIp(){
        return gethostbyaddr($_SERVER['REMOTE_ADDR']);
    }


} 