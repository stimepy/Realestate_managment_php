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
    private $envir_vars;
    private $template;
    private $rendered;

    /**
     * Basic constructor.  Loads twig and
     * todo cache
     *
     */
    public function __construct(){
        global $gx_config, $gx_library;

        $gx_library->loadLibraryFile($gx_config->config['libpath'].$gx_config->config['class'] , 'Autoloader.php');
        Twig_Autoloader::register();
        $this->loader = new Twig_Loader_String();
        $this->envir = new Twig_Environment($this->loader);
        $this->envir_vars = array();
        $this->template = array();
        $this->rendered = array();
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

    public function RenderTemplate($tid, $display = false){
       $this->rendered[$tid] = $this->template[$tid]->render($this->envir_vars[$tid]);
       if($display){
            return $this->DisplayTemplate($tid);
       }
       return true;
    }

    public function RenderTemplatesMulti($atid, $nexttidrender = true, $displaylast = false){
        $multi = 0;
        if(!is_array($atid)){
            //todo error!
            return false;
        }
        $tid_size = sizeof($atid);
        foreach($atid as $key => $value){
            $result = $this->RenderTemplate($value);
            if($nexttidrender){
                $nexttid=(isset($atid[$i+1]) && is_array($atid[$i+1]) )? $atid[$i+1][0] : false;
                if($nexttid != false){
                    $this->AddVariables($nexttid, $this->rendered[$atid[i][0]],$atid[i][1]);
                }
            }
        }

    }

    public function DisplayTemplate($tid){
        echo $this->rendered[$tid];
    }

}


?>