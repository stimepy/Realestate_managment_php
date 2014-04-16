<?php
/**
 * Capital Property Management System
 *
 * File: CUsers_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

class CUsers {
    public $user_info;
    private $loggedin = false;

    public function __construct(){

    }

   public function UserLogin(){
        global $gx_config, $gx_db, $gx_session;

        if($gx_session->CheckSession()){

            $sub = GetVar('login', '');
            if ($sub == 'go'){
                //Do some more here...
                $login_name = GetVar('user', '');
                //todo encerypt password.
                $password = GetVar('pass', '');

                //authentication
                $user = $gx_db->QuerySelectLimit($gx_config->language['tables']['users'],"user_id, user_name, user_email, user_login, user_level, user_number, user_class, user_super","`user_login` = '{$login_name}' AND `user_password` = '{$password}'");

                if (isset($user) && $user != false) {
                    $gx_session->SetSessionItem("user_login",true);
                    $gx_session->SetSessionItem("info", $user);
                    $gx_session->SetSessionItem("info", $user);
                    //redirecing to view sites
                    header("Location: ". $gx_config->global_config['default_location']);
                    exit;
                }
                else{
                    $this->loggedin = false;
                }
            }
            else if($gx_session->GetSessionItem('user_login') == true){
                $this->loggedin = $gx_session->GetSessionItem('user_login');
                $this->user_info = $gx_session->GetSessionItem('user_info');
            }
            else{
                echo 'here';
                $this->loggedin = false;
            }
        }
    }

    public function checkloggedin(){
        return $this->loggedin;
    }

} 