<?php
/**
 * Capital Property Management System
 *
 * File: CSite_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
/**
 * Class CSite
 *
 */
class CMaster {
    var $templates;
    var $vars;
    var $table;

    /**
     * @description set up the website configurations, settings, etc.
     * @access public
     */
    public function __construct() {
        global $gx_config, $gx_session, $gx_users, $gx_template;

        //loading the config
        $gx_config = new CConfig();
        $this->loadDatabase();

        //determine login status....
        $gx_session = new CSession();
        $gx_users = new CUsers();

        $gx_template = new CTemplate();
    }

    /**
     * Loads the database
     */
    private function loadDatabase(){
        global $gx_config, $gx_db;
        //make a connection to db
        if (isset($gx_config->config["database"])) {
            $gx_db = new CDatabase($gx_config->config["database"]);
        }
        else{
           //Error
        }
    }


    /**
     * @description Configuration is done, run the site.
     */
    function Run() {
        global $gx_session, $gx_TSM, $gx_users;

        $gx_users->UserLogin();
        if($gx_users->checkloggedin()){
            $this->DoEvents();
        }
        else{
            $this->loadTemplates(array('login', 'layout'), 'admin');
            $gx_TSM["AREA"]= "Login";
            $gx_TSM["MENU"] = $this->templates["login"]->blocks["MenuAdmin"]->output;
            $gx_TSM["CONTENT"] = $this->templates["login"]->blocks["Login"]->output;

        }
        //$gx_library->loadLibraryFile(_LIBPATH,"pb_events.php");
        //
        if (is_object($this->templates["layout"])) {
            echo $this->templates["layout"]->Replace($gx_TSM);
        }
    }




}
?>