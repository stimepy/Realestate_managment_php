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
    private $loader;
    private $envir;
    private $envir_vars = Array();
    private $template = Array();

    public function __construct(){
        global $gx_config, $gx_library;

        $gx_library->loadLibraryFile($gx_config->config['libpath'].$gx_config->config['class'] , 'Autoloader.php');
        Twig_Autoloader::register();
        //require_once '/path/to/vendor/autoload.php';
        $this->loader = new Twig_Loader_String();
        $this->envir = new Twig_Environment($this->loader);
    }

    public function AddTemplate($template, $name=NULL){
        if(!isset($name)){
            $name = 'Tid'.sizeof($this->template);
        }
        $this->templates[$name] = $this->envir->loadTemplate($template);
        return $name;
    }


    public function AddVariables($tid, $var, $name = NULL){
        if(!is_array($var)){
            if(!isset($name)){
                //todo add errors
                return false;
            }
            $var = [$name => $var];
        }
        if(is_array($this->envir_vars[$tid])){
            $this->envir_vars[$tid] = array_merge($this->envir_vars[$tid][0],$var);
        }
        else{
            $this->envir_vars[$tid] = $var;
        }
        return true;
    }

    public function RemoveVar($tid, $name){
        $this->envir_vars[$tid][$name] = NULL;
        return true;
    }

    public function RenderTemplates(){

    }


}


?>