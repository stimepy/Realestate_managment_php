<?php

class CLibrary {
	/**
	* unique library identifier
	*
	* @var string
	*
	* @access private
	*/
	var $name;

	/**
	* constructor which sets the lib`s name
	*
	* @param string $name	unique library identifier
	*
	* @return void
	*
	* @acces public
	*/
	function CLibrary($name) {
		$this->name = $name;
	}
}
?>