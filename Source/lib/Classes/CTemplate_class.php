<?php
/**
 * File description: Class file
 * Class: CTemplate
 * @author Kris Sherrerd  stimepy@aodhome.com
 * Modified by Kris Sherrerd
 * Last updated: 4/30/2014
 * Copyright (c) 2014
 * Version 1.0
 */
if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CTemplate
 * Using twig, creates and renders the templates.
 */
class CTemplate {
    private $template_path;
    private $envir;
    private $envir_vars;
    private $template;
    private $rendered;
    private $display_ready;
    private $templace_interator = 0;
    private $mystart_template;

    /**
     * Basic constructor.  Loads twig and
     * todo cache
     *
     */
    public function __construct(){
        global $gx_config, $gx_library;
        $theme = $gx_config['config']['theme']; //todo: set up for db to overwrite this.

        //$gx_library->loadLibraryFile($gx_config->config['paths']['vendor'], 'Autoloader.php');
        $this->template_path = $gx_config['paths']['themepath'].$theme;
       // $this->envir = new Twig_Environment($this->loader);
        $this->envir_vars = array();
        $this->template = array();
        $this->rendered = array();
        $this->display_ready='';
    }

    /**
     * Using Twig, adds a template, and assigned it a tid if not defined by $name
     * @param string $template
     * @param null string $name
     * @return string
     */
    public function AddTemplate($template, $name=NULL){
        if(!isset($name)){
            $name = 'Tid'.(sizeof($this->template)+1);
        }
        $this->template[$name] = $template; // $this->envir->loadTemplate($template);
        if(sizeof($this->template)==1){
            $this->mystart_template = $name;
        }
        return $name;
    }

    /**
     *  Get a template, and returns it
     * @param string $template
     * @param null string $name
     * @return string
     */
    public function getTemplate($name){
        if(isset($name)){
            return $this->template[$name]; // $this->envir->loadTemplate($template)
        }
        return $this->template[$this->templace_interator++]; // $this->envir->loadTemplate($template)
    }

    /**
     * Adds your content to a variable to it can later be rendered by the template renderer
     * @param string $tid
     * @param array? string $var
     * @param null string $name
     * @return bool
     */
    public function AddVariables($tid, $var, $name = NULL){
        if(!is_array($var)){
            if(!isset($name)){
                //todo add errors
                return false;
            }
            $var = [$name => $var];
        }

        if(isset($this->envir_vars[$tid]) && is_array($this->envir_vars[$tid])){
           $this->envir_vars[$tid] = array_merge($this->envir_vars[$tid], $var);
        }
        else{
            $this->envir_vars[$tid] = $var;
        }
        return true;
    }

    /**
     * @param string $tid
     * @param string $name
     * @return bool
     */
    public function RemoveVar($tid, $name){
        $this->envir_vars[$tid][$name] = NULL;
        return true;
    }

    /**
     * Deprecated
     * Renders the template, does not display by default
     * @param $tid
     * @param false bool $display
     * @return bool|void
     */
    public function RenderTemplate($tid, $display = false, $display_type = TEMPLATE_SHOW){
        //print_r($this->envir_vars[$tid]);
        $this->rendered[$tid] = $this->template[$tid]->render($this->envir_vars[$tid]);
        $this->envir_vars[$tid] = NULL;
       if($display){

            return $this->DisplayTemplate($tid, $display_type);
       }
       return true;
    }

     /**
     * Will display the rendered template(s)
     * @param string $tid
     */
    public function DisplayTemplate($tid, $display_type = TEMPLATE_SHOW){
        switch($display_type){
            case TEMPLATE_SHOW:
                $this->display_ready .= $this->rendered[$tid];
                echo $this->display_ready;
                break;
            case TEMPLATE_HOLD:
                $this->display_ready .= $this->rendered[$tid];
                break;
            case TEMPLATE_RETURN:
                return $this->rendered[$tid];
        }
    }

}// end Ctemplate class



?>