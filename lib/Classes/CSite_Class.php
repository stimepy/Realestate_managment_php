<?php
/**
 * Capital Property Management System
 *
 * File: CSite_class.php
 * Author: Kris Sherrerd
 * Copyright: 2014 by Kris Sherrerd
 * Version 0.2
 * Modified: 4/15/2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
/**
 * Class CSite
 *
 */
class CSite {
    var $admin;
    var $html;
    var $templates;
    var $vars;
    var $table;

    /**
     * @description set up the website configurations, settings, etc.
     * @param $xml
     * @param bool $admin
     * @access public
     */
    public function __construct($xml , $admin = false) {
        global $gx_CONF, $gx_config, $gx_session, $gx_db;

        //loading the config
        $gx_config = new CConfig($xml);

        //todo remove
        $gx_CONF = $gx_config->vars["config"];

        $this->loadDatabase();

        //determine login status....
        $gx_session = new CSession();
        //for the eventual loading of template
        $base = new CBase();
        $base->html = new CHtml();
        $this->html = $base->html;

        $this->vars = new CVars($gx_db , $gx_CONF["tables"]["vars"]);

        $base->vars = &$this->vars;
    }

    /**
     * @description Loads templates
     * @param $template string or array of strings
     */
    public function loadTemplates($template, $path){
        global $gx_config;
       if(isset($gx_config->config["paths"])){
           $my_path = ( isset($gx_config->config["paths"][$path]) )? $gx_config->config["paths"][$path] : "" ;
           /* if there are multiple files we load them all */
           if(is_array($template)){
               for($i = 0; $i<sizeof($template); $i++){
                 /*debug*///echo $gx_config->config["paths"]["templatepath"]. $my_path . $gx_config->config["forms"][$template[$i]];
                   $this->templates[$template[$i]] = new CTemplate($gx_config->config["paths"]["templatepath"]. $my_path . $gx_config->config["forms"][$template[$i]]);
               }
           }
           else{
                $this->templates[$template] = new CTemplate($gx_config->config["paths"]["templatepath"]. $my_path . $gx_config["forms"][$template]);
           }
        }
        else{
            //error
        }
    }

    /**
     *
     */
    private function loadDatabase(){
        global $gx_config, $gx_db;
        //make a connection to db
        if (isset($gx_config->config["database"])) {
            $gx_db = new CDatabase($gx_config->config["database"]);
        }
        else{
           //Error
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
        global $gx_session, $gx_TSM;
        $gx_TSM = [];
        $gx_TSM["TITLE"] = "In devlopment";

        if($gx_session->getLoginStatus()){
            $this->DoEvents();
        }
        else{
            $this->loadTemplates(array('login', 'layout'), 'admin');
            $gx_TSM["AREA"]= "Login";
            $gx_TSM["MENU"] = $this->templates["login"]->blocks["MenuAdmin"]->output;
            $gx_TSM["CONTENT"] = $this->templates["login"]->blocks["Login"]->output;

        }
        //$gx_library->loadLibraryFile(_LIBPATH,"pb_events.php");
        //
        if (is_object($this->templates["layout"])) {
            echo $this->templates["layout"]->Replace($gx_TSM);
        }
    }

    private function DoEvents() {
        global $_CONF, $gx_config , $gx_TSM, $gx_db, $gx_session;
       //load the layout.
        $this->loadTemplates('layout', 'admin');
        $event->$this;

        //set the menu as appropraite
        if ($gx_session->user_info["user_level"] == 0) {
            $this->loadTemplates('login', 'admin');
            $gx_TSM["MENU"] = $this->templates["login"]->blocks["MenuAdmin"]->output;
        }
        $task_user=GetVar("task_user", "");
        if (!$task_user){
            $task_user = $gx_session["user"];
        }

        //if($gx_session->user_info["user_level"] == 1) {
            //$_CONF["forms"]["adminpath"] = $_CONF["forms"]["userpath"];
        //}

        switch (GetVar("sub", "")){
            case "logout":
                $gx_session->killsession();
                header("Location: index.php");
                $this->loadTemplates('login', 'admin');
                return $gx_TSM["CONTENT"] = $this->templates["login"]->EmptyVars();
                break;


            case "expenses":
                //todo make more appropriate expenses
            case "properties":

                if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
                    die("i'm on it");
                    $task = new CSQLAdmin("expenses", $_CONF["forms"]["admintemplate"],$event->db,$event->tables , $extra);
                    $extra["details"]["fields"]["button"] = $task->DoEvents();
                }

                $data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$event->db,$event->tables,$extra);

                if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
                    $expense = $event->db->QuerySelectLimit($event->tables[expenses],'sum(expense_cost)', "expense_prop ='{$_GET[prop_id]}' " .
                        ($_GET[date_year] ? " AND expense_date_year ={$_GET[date_year]} " : '') .
                        ($_GET[date_month] ? " AND expense_date_month ={$_GET[date_month]} " : ''));
                    //$expense = $event->db->QFetchArray("SELECT sum(expense_cost) FROM `{$event->tables[expenses]}` WHERE expense_prop ='{$_GET[prop_id]}' " .
                    //			($_GET[date_year] ? " AND expense_date_year ={$_GET[date_year]} " : '') .
                    //			($_GET[date_month] ? " AND expense_date_month ={$_GET[date_month]} " : ''));
                    $property = $event->db->QuerySelectLimit($event->tables[properties], '*', "prop_id='{$_GET[prop_id]}'");
                    //$property = $event->db->QFetchArray("SELECT * FROM {$event->tables[properties]} WHERE prop_id='{$_GET[prop_id]}'");

                    $data->forms["forms"]["details"]["fields"]["expense_total"]= array(

                        "type" => "text",
                        "title" => "Expenses Total",
                        "action" => "price",
                        "preffix" => "$",
                        "forcevalue" => $expense['sum(expense_cost)']
                    );

                    $data->forms["forms"]["details"]["fields"]["expense_income"]= array(

                        "type" => "text",
                        "title" => "Leased Amount",
                        "action" => "price",
                        "preffix" => "$",
                        "forcevalue" => $property['prop_leased_amount']
                    );

                    if ($_GET["date_month"] && $_GET["date_year"])
                        $data->forms["forms"]["details"]["fields"]["expense_income2"]= array(

                            "type" => "text",
                            "title" => "Profit",
                            "action" => "price",
                            "preffix" => "$",
                            "forcevalue" => $property['prop_leased_amount'] - $expense['sum(expense_cost)']
                        );

                }

                return $data->DoEvents("","",$_POST);
                break;

            default:
                return "Properties Expenses Administration Area";
                break;
        }
    }

}
?>