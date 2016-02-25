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

    /**
     * @description: Takes a string of the module and sees if it can find that module in the installed modules
     * @param string $module
     * @return bool
     */
    public function isInstalled($module){
        foreach($this->myMods as $mod){
            if($mod['mod_name'] == $module && $mod['mod_installed']){
                return true;
            }
        }
        return false;
    }

    public function install_module($properties, $table_creation){
        global $gx_db, $gx_config;

        $tables_sql =array();
        $build_tmp = '';

        foreach($table_creation as $key => $value){
            $result =createtable($key, $value);
        }
        if($result != false){
            $gx_db->QueryInsert($gx_config['tables']['module'],$properties);
        }



    }
} 