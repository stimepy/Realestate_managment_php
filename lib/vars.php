<?php

class CVars {
	var $database;
	var $table;
	var $data = array();
	var $modif = FALSE;

	function CVars($database,$table) {
		//$this->database = (is_object($database)) ? &$database : new CDatabase($database);
		if (is_object($database))
			$this->database = &$database;
		else
			$this->database = new CDatabase($database);

		$this->table = $table;

		return $this->Load();
	}

	function Load() {
		$vars = $this->database->QFetchRowArray("SELECT * FROM `$this->table`");

		if (is_array($vars))
			foreach ($vars as $var)
				$this->data[$var["name"]] = $var["value"];

		
	}

	function SetAll($var) {
		$this->data = array_merge ($this->data ,$var);
		$this->modif = TRUE;
	}
	

	function Set($name,$value,$force = FALSE) {
		$value = addslashes($value);
		 if ($force == TRUE) {
				 $this->database->Query("UPDATE `$this->table` SET `value` = '$value' WHERE (`name` = '$name')");
			 
			 if ($this->database->AffectedRows() == 0) {
				 $this->database->Query("INSERT INTO `$this->table` (`name`,`value`) VALUES ('$name','$value')");
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

			// build names and values
			$values = implode(", ",$values);

			// do the nasty things
			$this->database->Query("DELETE FROM `$this->table`");
			$this->database->Query("INSERT INTO `$this->table` (`name`,`value`) VALUES $values");
			
		}
	}
}
?>