<?php
/**
 * File description: Class file
 * Class: CTemplate
 * Modified by Kris Sherrerd
 * Last updated: 4/17/2014
 * Copyright 2014
 * Version 1.0
 */
if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CTemplate
 */
class CTemplate {

    public function __construct(){
        global $gx_config, $gx_library;

        $gx_library->loadLibraryFile($gx_config->config['libpath'].$gx_config->config['class'] , 'Autoloader.php');
        Twig_Autoloader::register();
        //require_once '/path/to/vendor/autoload.php';
        $loader = new Twig_Loader_String();
        $twig = new Twig_Environment($loader);
        echo $twig->render('Hello {{ name }}!', array('name' => 'Fabien'));
    }
}


?>