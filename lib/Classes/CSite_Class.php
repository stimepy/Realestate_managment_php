<?php
/**
  * Filename: CSite_Class.php
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

    /**
     * @description
     * @param $xml
     * @param bool $admin
     * @access public
     */
    public function __construct($xml , $admin = false) { //CSite
        global $gx_CONF;

        //loading the config
        $config = new CConfig($xml);
        print_r($config->vars["config"]);
        die("I'm on this");
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
                        $this->templates[$key] = new CTemplate($gx_CONF["templates"]["path"] . $gx_CONF["templates"][$key]);
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

        $gx_library->loadLibraryFile(_LIBPATH,"pb_events.php");
        $_TSM["PB_EVENTS"] = @DoEvents($this);
        if (is_object($this->templates["layout"])) {
            echo $this->templates["layout"]->Replace($_TSM);
        }
    }

    private function DoEvents($event) {
        global $_CONF , $_TSM, $db;

        $_TSM["MENU"] = "";

        //checking if user is logged in
        if (!$_SESSION["minibase"]["user"]) {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                //autentificate
                $user = $event->db->QuerySelectLimit($event->tables[users],'*',"`user_login` = '{$_POST[user]}' AND `user_password` = '{$_POST[pass]}'");
                //$user = $event->db->QFetchArray("select * from {} where );

                if (is_array($user)) {
                    $_SESSION["minibase"]["user"] = 1;
                    $_SESSION["minibase"]["raw"] = $user;

                    //redirecing to viuw sites
                    header("Location: $_CONF[default_location]");
                    exit;
                }
                else{
                    return $event->templates["login"]->blocks["Login"]->output;
                }

            } else{
                //   echo is_object($event->templates);
                return $event->templates["login"]->blocks["Login"]->output;
            }
        }
        if ($_SESSION["minibase"]["raw"]["user_level"] == 0) {
            $_TSM["MENU"] = $event->templates["login"]->blocks["MenuAdmin"]->output;
        } else {
            $_TSM["MENU"] = $event->templates["login"]->blocks["MenuUser"]->output;
        }

        if (!$_POST["task_user"])
            $_POST["task_user"] = $_SESSION["minibase"]["user"];

        if($_SESSION["minibase"]["raw"]["user_level"] == 1) {
            $_CONF["forms"]["adminpath"] = $_CONF["forms"]["userpath"];
        }

        switch ($_GET["sub"]) {
            case "logout":
                unset($_SESSION["minibase"]["user"]);
                header("Location: index.php");

                return $event->templates["login"]->EmptyVars();
                break;

            case "properties":
            case "expenses":

                if (($_GET["sub"] == "properties") && ($_GET["action"] == "details")) {
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

            case "users":

                if ((!$_GET["action"])&&($_SESSION["minibase"]["raw"]["user_level"] != 0 )) {
                    $_GET["action"] = "details";
                }

                if ($_SESSION["minibase"]["raw"]["user_level"] == 1) {
                    $_GET["user_id"] = $_SESSION["minibase"]["raw"]["user_id"];
                    $_POST["user_id"] = $_SESSION["minibase"]["raw"]["user_id"];
                }

                $data = new CSQLAdmin($_GET["sub"], $_CONF["forms"]["admintemplate"],$event->db,$event->tables);
                return $data->DoEvents();
                break;

            default:
                return "Properties Expenses Administration Area";
                break;
        }
    }

}
?>