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
    private $loader;
    private $envir;
    private $envir_vars;
    private $template;
    private $rendered;
    private $display_ready;

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
            $name = 'Tid'.sizeof($this->template)+1;
        }
        $this->templates[$name] = $this->envir->loadTemplate($template);
        return $name;
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
        if(is_array($this->envir_vars[$tid])){
            $this->envir_vars[$tid] = array_merge($this->envir_vars[$tid][0],$var);
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
     * Renders the template, does not display by default
     * @param $tid
     * @param false bool $display
     * @return bool|void
     */
    public function RenderTemplate($tid, $display = false, $display_type = TEMPLATE_SHOW){
       $this->rendered[$tid] = $this->template[$tid]->render($this->envir_vars[$tid]);
        $this->envir_vars[$tid] = NULL;
       if($display){
            return $this->DisplayTemplate($tid, $display_type);
       }
       return true;
    }

    /**
     * Takes an array in the format of array(array(tid,name)[array(tidb,name),...],tid)
     * Will render each of your templates, will ALSO render a template then take that
     * rendered template as a piece of content to put into a variable to be rendered.
     *  ie. You render tid, and using name set name as the variable to be rendered by template of
     *  tidb
     * if you choose to display will use the last tid as the ultimate renderer.
     * @param array $atid
     * @param bool $nexttidrender
     * @param bool $displaylast
     * @return bool
     */
    public function RenderTemplatesMulti($atid, $nexttidrender = true, $displaylast = false, $display_type = TEMPLATE_SHOW){
        $multi = 0;
        if(!is_array($atid)){
            //todo error!
            return false;
        }
        $curtid = '';
        $count = 1;
        foreach($atid as $value){
            if(is_array($value)){
                $curtid = $value[0];
                $curname = $value[1];
            }
            else{
                $curtid = $value;
            }
            $this->RenderTemplate($curtid);
            if($nexttidrender){
                $nexttid=(isset($atid[$count]) && is_array($atid[$count]) )? $atid[$count][0] : false;
                if($nexttid != false){
                    $this->AddVariables($nexttid, $this->rendered[$curtid],$curname);
                }
            }
            $count++;
        }
        if($displaylast){
            $this->DisplayTemplate($curtid, $display_type);
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


function CreateHeader(){
    global $gx_template;
    $tid = $gx_template->AddTemplate('Main_head.tpl');
    //todo create menus, title, etc.  not manually!
    $gx_template->AddVariables($tid, 'Capital Property Management',  'title' );
    $gx_template->AddVariables($tid, ['url' => 'index.php?mod=properties', 'link_name' => 'Properties' ],  'buttons' );

    $gx_template->RenderTemplate($tid, $display = true, $display_type = TEMPLATE_HOLD);
}

function CreateFooter(){
    global $gx_template;
    $tid = $gx_template->AddTemplate('Main_Footer.tpl');
    //todo create menus, title, etc.  not manually!
    $gx_template->AddVariables($tid, 'Capital Property Management',  'website' );
    $gx_template->AddVariables($tid, ['link' => 'index.php?mod=properties', 'title' => 'Properties' ],  'footlinks' );

    $gx_template->RenderTemplate($tid, $display = true);
}

?>