<?php
/**
 * File description: Class file
 * Class: CDatabase
 * Modified by Kris Sherrerd
 * Last updated: 4/10/2014
 * Changes Copyright 2014
 * Version 1.0
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
DEFINE('DBSELECT', "select");
DEFINE('DBCREATE', "create");
DEFINE('DBINSERT', "insert");
DEFINE('DBUPDATE', "update");
DEFINE('DBDELETE', "delete");
//DEFINE('DBSELECT', "other");
/**
 * Class CDatabase
 */
class CDatabase{
     private $conn_id;
     private $current_db;
     public $num_queries;
     private $results;

    /**
     * @description: initializes module and connects to the database
     * @param $server
     * @param null $login
     * @param null $password
     * @param null $defualt
     *
     */
    public function __construct($server, $login=NULL, $password=NULL, $defualt=NULL) {
         if(is_array($server)){
             $params = $server;
             unset($server); //unset incase $server is not set.
             extract($params, EXTR_OVERWRITE);// makes $server, $login, $password, default, and password
         }
         //verifies these have been set, if not, defaults them.
         if(!isset($server,$login, $password, $default)){
             $server = (isset($server))? $server : 'localhost';
             $login = (isset($login))? $login : 'username';
             $password = (isset($password))? $password : '';
             $default = (isset($default))? $default : 'propertymanagement';
         }
         $this->Connect($server, $login, $password, $default);
     }

     /**
     * connects to the database
     * @param array $connect_params connection parameters
     * @return void
     * @access private
     */
    private function Connect($server, $login, $password, $default) {
        $this->conn_id = mysqli_connect($server, $login, $password, $default);
        //error handling!
        if ($this->conn_id->connect_error) {
            die('Connect Error (' . $this->conn_id->connect_errno . ') '
                . $$this->conn_id->connect_error);
        }

        $this ->current_db = $default;
        return true;
    }

     /**
     * @description closes the database connection
     * @return void
     * @access public
     */
     public function Dbclose() {
         $this->conn_id->close();
     }

     /**
     * @description selects and sets the current database
     * @param string $database
     * @return void
     * @access public
     */
     public function SelectDB($database) {
          //mysql_select_db($database,$this->conn_id)
         $this->conn_id->select_db($database);
          $this->current_db = $database;
     }

     /**
     * queries the database

     * @param string $query actual sql query
     * @return bool or fail
     * @access private
     */
    Private function callQuery($query,$returntype = "", $retresults = false, $modify = false) {
        $this->num_queries++;
        //determine what if any type of return;
        switch($returntype){
            case 1:
              $type = MYSQLI_USE_RESULT;
              break;
            case 2:
              $type = MYSQLI_STORE_RESULT;
              break;
            default:
              $type=false;
              break;
        };
        if($type){
            $this->results = $this->conn_id->query($query,$type);
        }
        else{
            $this->results = $this->conn_id->query($query);
        }
        //error of some sort, kill it
        if($this->conn_id->errno){
            die('Sql error: '.$query . ' :: '.$this->conn_id->error);
        }
    /*    if ($modify == true){
            $this->modif = TRUE;
        }*/
        return true;
    }

    /**
     * @description fetches the results.  However you wants em.
     * @param string $how
     * @param mixed $additional
     * @access Public
     */
    public function fetch($how, $additional=false){
        switch($how){
            case 'array':
                if($additional){
                    $temp = $this->results->fetch_array($additional);
                    while($temp){
                        $return_value[]=$temp;
                        $temp = $this->results->fetch_array($additional);
                    }
                }
                else{
                    $temp = $this->results->fetch_array();
                    while($temp){
                        $return_value[] = $temp;
                        $temp = $this->results->fetch_array();
                    }
                }
                unset($temp);
                break;
            case 'row':
                $return_value = $this->results->fetch_row();
                break;
        }
        if(isset($return_value)){
            return $return_value;
        }
        return false;
    }

    /**
     * @description: returns the last insert_id
     * @return mixed
     * @access public
     */
    public function InsertID() {
        return $this->conn_id->insert_id;
    }

    /**
     * @description returns count of affected rows
     * @return mixed
     * @access public
     */
    public function AffectedRows() {
        return  $this->conn_id->affected_rows;
    }

     /**
     * returns the number of rows from a table based on a certain [optional]
     * where clause
     *
     * @param string $table   table in which to count rows
     * @param string $where_clause optional where clause [see sql WHERE clause]
     *
     * @return int row count
     *
     * @access public
     */
     public function RowCount($table,$where_clause = "") {
         $query = "SELECT COUNT(*) FROM `$table` $where_clause";
         $this->callQuery($query,0,false,false);
         $results=$this->fetch('row', MYSQLI_NUM);
         $this->clearResults();
         return $results[0];
     }

     /**
     * returns an array w/ the tables fields
     * @param $table database table from which to get rows
     * @return array
     * @access public
     */
    public function GetTableFields($table) {
        $query = "SHOW FIELDS FROM `$table`";
        $this->callQuery($query);
        $fields = $this->fetch('array');
        $this->clearResults();
        foreach ($fields as $field){
            $ret_val[] = $field["Field"];
        }
        return $ret_val;
    }

     /**
     * fetches a row from a table based on a certain id using the SELECT SQL query
     *
     * @param string $table  table in which to perform select
     * @param int $id   row id to fetch
     * @param string $fields  comma separated list of row fields to fetch [defaults to `*' all]
     * @param int $return_type row return type DB_RT_ARRAY|DB_RT_OBJECT [defaults to DB_RT_ARRAY]
     *
     * @return array w/ the fetched data or NULL if id not found
     *
     * @access public
     */
     public function selectRow($table, $fields = "*", $where='') {
      // build query
        $where =( $where != '')? "where {$where}": "" ;
        $query = "SELECT $fields FROM `$table` $where";
        $this->callQuery($query);
        $data = $this->fetch('row');
        $this->clearResults();
        return $data;
     }

     /**
     * @description complex fetch row array w/ WHERE/LIMIT/ORDER SQL clauses and page modifier
     * @param string $table   table to fetch rows from
     * @param string $fields   comma separated list of row fields to fetch
     * @param string $where_clause SQL WHERE clause [use empty to ignore]
     * @param int $start    limit start
     * @param int $count    number of rows to fetch
     * @param bool $pm    page modifier. if set to TRUE [default] $start becomes the page
     * @param string $order_by  field[s] to order the result by [defaults to void]
     * @param string $order_dir  order direction. can be ASC or DESC [defaults to ASC]
     * @return array w/ fetched rows or NULL
     * @access public
     */
    public function QuerySelect($table,$fields, $where_clause="", $start=-1,$count=-1, $order_by = "",$order_dir = "ASC") {

        $limit_clause = $this->defineLimit($start, $count);
        // setup order clause
        $order_clause = ($order_by != "") ? "ORDER BY $order_by " . (in_array($order_dir,array("ASC","DESC")) ? "$order_dir " : "") : "";
        // setup where clause
        $where_clause = ($where_clause != "") ? "WHERE {$where_clause} " : "";

        // build query
        $query = "SELECT $fields FROM `$table` {$where_clause}{$order_clause}{$limit_clause}";
        //Run query
        $this->callQuery($query);
        // fetch rows
        $results = $this->fetch('array');
        //clear results;
        $this->clearResults();

        if($results != NULL){
            return $results;
        }
        return false;
    }


    /**
     * @param int $start
     * @param int $countof
     * @return string
     */
    private function defineLimit($start,$countof){
        $limit = '';
         if($countof !=-1 && $start != -1){
            $countof = ($countof == "") ? 0 : $countof;
            // recompute $start if page modifier set
            $limit = ($start >= 0) ? "LIMIT $start,$countof" : "";
        }
        return $limit;
    }


    /**
     * @param $table
     * @param $options
     * @param $type
     * @return bool
     * remove ASAP
     */
    public function UndefQuery($table, $options, $type){
       if(!isset($type)){
           return false;
       }
        switch($type){
            case "update":
                $query = "UPDATE {$table} set {$options}";
                break;
            case "insert":
                if(is_array($options)){
                    $query = "INSERT INTO (". explode($options[0], ',') .") VALUES(". explode($options[1], ',') .")";
                }
                else{
                    $query = "INSERT INTO  VALUES(". explode($options, ',') .")";
                }
        }

        $this->callQuery($query);

    }

     /**
     * @description builds and performes a SQL INSERT query based on the user data
     * @param string $table table in which to perform insert
     * @param array $fields associative array w/ the row fields to be inserted
     * @return void
     * @access public
     */
    public function QueryInsert($table,$fields) {
        if(is_array($fields)){
        // prepare field names and values
            foreach ($fields as $field => $value){
            // check for valid fields
                     $values[] = "`$field`";
                     $items[] = is_numeric($value) ? $value : $this->escapesctring($value);
            }
            $query = "INSERT INTO `$table`(".implode(',',$values).") VALUES(".implode(',',$items).")";
        }
        else{
            foreach($fields as $field){
                $items[] =is_numeric($field) ? $field : $this->escapesctring($field);
            }
            $query = "INSERT INTO `$table` VALUES(".implode(',',$items).")";
        }
       // perform query
        $this->callQuery($query,'',false,true);

        return $this->InsertID();
    }

     /**
     * @description builds and performs a SQL UPDATE query based on the user data
     * @param string $table   table in which to perform update
     * @param array $fields   associative array w/ the fields to be updated
     * @param string $where_clause update where clause [see SQL WHERE clause]
     * @return bool
     * @access public
     */
    public function QueryUpdate($table,$fields,$where_clause) {
        if (is_array($fields)) {
            // first get the tables fields
            $table_fields = $this->GetTableFields($table);

            // prepare query
            foreach ($fields as $field => $value){
            // check for valid fields
                if (in_array($field,$table_fields)){
                 $pairs[] = "`$field` = " . (is_numeric($value) ? $value : "'" . addslashes($value) . "'");
                //     $values[] = ;
                }
            }
            // build and perform query
            if (is_array($pairs)){
                $new_values =  implode(", ",$pairs);
           }
            else{
                //error of some sort
            }
        }
        else{

        }
        $this->Query("UPDATE `$table` SET " . $new_values . " WHERE($where_clause)", '', false, true);
        return true;
    }

    /**
     * @description deletes stuff
     * @param $table
     * @param $where
     * @return bool
     */
    function deleteQuery($table, $where = ''){
        $where = ($where != '')? "where {$where}": "";
        $query = "DELETE FROM `{$table}` {$where}";
        return $this->callQuery($query);
    }

    /**
     * @description Frees the result set from the query.
     * @return bool
     */
    function clearResults(){
        if($this->results){
            $this->results->free();
            $this->resutls = NULL;
            return true;
        }
        return false;
    }

    /*
     * Remove as Quickly as possible
     */
    function Queryworkaround($query){
        $this->callQuery($query);
        $results = $this->fetch('array');
        return $results;
    }

    public function escapesctring($myString){
        if(isset($myString)){
            //error
            return;
        }
        return "'".$this->conn_id->real_escape_string($myString)."'";

    }

    public function createtable($table, $columns){
        $build_tmp = '';
        $tab_siz = sizeof($columns);
        $build_tmp += "Create table {$table} ( ";
        for($i=0;$i<$tab_siz;$i++){
            $build_tmp += $columns[$i];
            if(($i+1) != $tab_siz) {
                $build_tmp += ", ";
            }
        }
        $build_tmp += ";";
        if($this->callQuery($build_tmp,'')){
            return true;
        }
        return false;
    }
}
?>
