<?php
/**
 * Capital Property Management System
 *
 * File: Modules_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

class Modules {
    private $myMods;
    public function __construct(){
        global $gx_library, $gx_config, $gx_db;

        $this->myMods=$gx_db->QuerySelect($gx_config->language['tables']['modules'], 'mod_path,mod_id,mod_name,mod_active,mod_installed');

    }

    public function finduninstalledmods(){
        global $gx_config,  $gx_db;
        $dir = @opendir($gx_config->config['modulepath']);
        while(false !== ($file = readdir($dir))){
            if($file != '.' && $file != '..'){
                if(is_dir($file) && !in_array($file, $this->myMods)){
                    //todo later!
                }

            }
        closedir($dir);
        }
    }

} 