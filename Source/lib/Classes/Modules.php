<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 4/21/14
 * Time: 9:59 PM
 */

class Modules {
    private $myMods;
    public function __construct(){
        global $gx_library, $gx_config, $gx_db;

        $this->myMods=$gx_db->QuerySelect($gx_config->language['tables']['modules'], 'mod_path, mod_name, mod_installed, mod_id');

    }

    private function findmods(){
        global $gx_config, $gx_db;
        $dir = @opendir($gx_config->config['modulepath']);
        while(false !== ($file = readdir($dir))){
            if($file != '.' && $file != '..'){
                if(is_dir($file) && !in_array($file, $this->myMods)){
                    $gx_db->
                }

            }
        closedir($dir);
        }
    }

} 