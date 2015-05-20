<?php
/**
 * Capital Property Management System
 *
 * File: CMaster_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
/**
 * Class CMaster
 *
 */
class CMaster {

    /**
     * @description set up the website configurations, settings, etc.
     * @access public
     */
    public function __construct() {
        global $gx_config, $gx_session, $gx_users, $gx_template, $gx_db;

        //loading the config
        $gx_config = new CConfig();

        //make a connection to db
        if (isset($gx_config->config["database"])) {
            $gx_db = new CDatabase($gx_config->config["database"]);
        }
        else{
            //Error
        }

        //determine login status....
        $gx_session = new CSession();
        $gx_users = new CUsers();

        $gx_template = new CTemplate();
    }


    /**
     * @description Configuration is done, run the site.
     */
    public function findAction() {
        global $gx_session, $gx_users, $gx_module;
        if(!$gx_users->checkloggedin()){
            $gx_users->GoLogin();
        }

        //first figure out if we are doing a core action, or a module action
        $site = GetVar('core', false);
        $module = GetVar('mod', false);
        if(!$site && ! $module){
            //display a nice menu template.
        }
        elseif($site && $module){
            //Can't be both, ban the ip, and user
        }

        if($site){
            switch($site){
                case "login":
                default:
                    $gx_users->GoLogin();
                    break;
                case "admin_loc":
                    echo '<meta http-equiv="refresh" content="0; url=/admin/admin.php" />';
                    exit;
                    break;

            }
        }
        elseif($module){
            $gx_module = new Modules();
            if($gx_module->isInstalled($module)){
                $gx_module->getModuleStart($module);
            }
            else{
                //nope
            }
        }

    }




}
?>