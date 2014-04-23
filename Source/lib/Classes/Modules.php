<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 4/21/14
 * Time: 9:59 PM
 */

class Modules {

    public function __construct(){
        global $gx_library, $gx_config;
        $gx_library->FindNamedFiles($file_array, $gx_config->config['modulepath'],,array('plugin.php','install.php')$depth =1 );
        for(i=0;i<sizeof($file_array); i++){}
        names[i]=strchr($file_array[i], '/');
    }

} 