<?php
/**
  * Filename: CBase_Class.php
 * last Modified: 4/8/14
 * Version: 1.0
 */

/**
 * Class CSite
 *
 */
class CSite {
    var $admin;
    var $html;
    var $templates;
    var $db;
    var $vars;
    var $table;

    /*********************
     * description
     * @param xml(string), admin(bool)
     * @return
     * @access
     *******************/
    function __construct($xml , $admin = false) { //CSite
        global $gx_CONF, $db;

        //loading the config
        $config = new CConfig($xml);
        $gx_CONF = $config->vars["config"];
        $this->loadDatabase();

        $this->admin = true; // todo automate this so admin is determined by a database query //$admin;
        $this->loadTemplates();

    }

    /**
     *
     */
    private function loadTemplates(){
        global $gx_CONF, $base;
        if ($this->admin) {
            if(isset($gx_CONF["templates"]["admin"])){
                foreach ($gx_CONF["templates"]["admin"] as $key => $val) {
                    if ($key != "path"){
                        $this->templates[$key] = new CTemplate($gx_CONF["templates"]["admin"]["path"] . $_CONF["templates"]["admin"][$key]);
                    }
                }
            }
            else{
                //todo error
            }
        }
        else {
            if (isset($gx_CONF["templates"])) {
                foreach ($gx_CONF["templates"] as $key => $val) {
                    if (($key != "path" ) && ($key != "admin")){
                        $this->templates[$key] = new CTemplate($_CONF["templates"]["path"] . $_CONF["templates"][$key]);
                    }
                }//foreach
            }//fi
            else{
                //todo error
            }
        }//esle

        $base = new CBase();
        $base->html = new CHtml();
        $this->html = $base->html;
    }

    /**
     *
     */
    private function loadDatabase(){
        global $gx_CONF, $db, $base, $gx_tables;
        //make a connection to db
        if (isset($gx_CONF["database"])) {
            $db = new CDatabase($gx_CONF["database"]);

            //vars only if needed
            if ($gx_CONF["tables"]["vars"]) {
                $this->vars = new CVars($this->db , $gx_CONF["tables"]["vars"]);
                $base->vars = &$this->vars;
            }

            $this->tables = $gx_CONF["tables"]; // todo get rid of local tables variable.
            $gx_tables = $this->tables;
        }
        else{
            //error
        }
    }

    /**
     * depreciated  4/9/2014
     */
    function TableFiller($item) {
       // if (file_exists("pb_tf.php")) {
       //     include("pb_tf.php");
       // }
    }

    /**
     * @description Configuration is done, run the site.
     */
    function Run() {
        global $_TSM, $gx_library;
        //include("pb_events.php");
        $gx_library->loadLibraryFile(_LIBPATH,"pb_events.php");
        $_TSM["PB_EVENTS"] = @DoEvents($this);
        if (is_object($this->templates["layout"])) {
            echo $this->templates["layout"]->Replace($_TSM);
        }
    }

}
?>