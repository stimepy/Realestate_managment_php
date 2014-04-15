<?php
/**
 * File description: Class file
 * Class: CVars
 * Modified by Kris Sherrerd
 * Last updated: 4/10/2014
 * Changes Copyright 2014 by Kris Sherrerd
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CVars
 */
class CVars {
	var $data = array();
	var $modif = FALSE;

	function CVars($database,$table) {
		return $this->Load();
	}

	function Load() {
        global $gx_db, $gx_config;
        $vars = $gx_db->QuerySelectLimit($gx_config->language['tables']['vars'],'*');

		if (is_array($vars))
			foreach ($vars as $var){
				$this->data[$var["name"]] = $var["value"];
            }
	}

	function SetAll($var) {
		$this->data = array_merge ($this->data ,$var);
		$this->modif = TRUE;
	}
	

	function Set($name,$value,$force = FALSE) {
		$value = addslashes($value);
		 if ($force == TRUE) {
				 $this->database->QueryUpdate($this->table, array('value' => $value), "`name` = '$name'");
			 if ($this->database->AffectedRows() == 0) {
				 $this->database->QueryInsert($this->table, array('name'=>$name,'value' =>$value));
			 }
		 }

		 $this->data["$name"] = $value;
		 $this->modif = TRUE;
	}

	function Get($name) {
		return $this->data["$name"];
	}

	function Save() {
		// any modifications?
		if ($this->modif == TRUE) {
			// prepare names and values
			foreach ($this->data as $name => $val) {
				$val = addslashes($val);
				$values[] = "('$name','$val')";
			}

			// do the nasty things
			$this->database->deleteQuery($this->table);
            $this->database->QueryInsert($this->table, $values);
		}
	}
}
?>