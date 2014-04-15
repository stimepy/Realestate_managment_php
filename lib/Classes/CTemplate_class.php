<?php
/**
 * File description: Class file
 * Class: CXMLParser
 * Modified by Kris Sherrerd
 * Last updated: 4/9/2014
 * Changes Copyright 2014
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

/**
 * Class CTemplate
 */
class CTemplate {
	/**
	* template source data
	* @var string
	*/
	var $input;

	/**
	* template result data
	* @var string
	*/
	var $output;

	/**
	* template blocks if any
	* @var array
	*/
	var $blocks;

	/**
	* @discription: constructor which autoloads the template data
	* @param string $source			source identifier; can be a filename or a string var name etc
	* @param string $source_type	source type identifier; currently file and string supported
	* @return void
	* @acces public
	*/
	public function __construct($source,$source_type = "file") {
		$this->Load($source,$source_type);
	}

	/**
	* @description: load a template from file. places the file content into input and output also setup the blocks array if any found
	* @param string $source			source identifier; can be a filename or a string var name etc
	* @param string $source_type	source type identifier; currently file and string supported
	* @return void
	* @acces public
	*/
	public function Load($source,$source_type = "file") {
        global $gx_library;
		switch ($source_type) {
			case "file":
				// get the data from the file
				$data = $gx_library->loadHtmFile($source);
				//$data = str_Replace('$','\$',$data);
			break;
			case "rsl":
			case "string":
				$data = $source;
			break;
	    };

		// blocks are in the form of <!--S:BlockName-->data<!--E:BlockName-->
		preg_match_all("'<!--S\:.*?-->.*?<!--E\:.*?-->'si",$data,$matches);

		// any blocks found?
		if (count($matches[0]) != 0){
			// iterate thru `em
			foreach ($matches[0] as $block) {
				// extract block name
				$name = substr($block,strpos($block,"S:") + 2,strpos($block,"-->") - 6);

				// cleanup block delimiters
				$block = substr($block,9 + strlen($name),strlen($block) - 18 - strlen($name) * 2);

				// insert into blocks array
				$this->blocks["$name"] = new CTemplate($block,"string");
			}
        }

		// cleanup block delimiters and set the input/output
		$this->input = $this->output = preg_replace(array("'<!--S\:.*?-->(\r\n|\n|\n\r)'si","'<!--E\:.*?-->(\r\n|\n|\n\r)'si"),"",$data);
	}

	/**
	* @description replace template variables w/ actual values
	* @param array $vars	array of vars to be replaced in the form of "VAR" => "val"
	* @param bool $clear	reset vars after replacement? defaults to TRUE
	* @return string the template output
	* @access public
	*/
	public function Replace($vars,$clear = TRUE) {
		if (is_array($vars)) {
			foreach ($vars as $key => $var) {
				if (is_array($var)) {
					unset($vars[$key]);
				}				
			}			
		}
		// init some temp vars
		$patterns = array();
		$replacements = array();

		// build patterns and replacements
		if (is_array($vars)){
			// just a small check		
			foreach ($vars as $key => $val) {
				$patterns[] = "/\{" . strtoupper($key) . "\}/";

				//the $ bug
				$replacements[] = str_replace('$','\$',$val);
			}
        }
		// do regex
		$result = $this->output = preg_replace($patterns,$replacements,$this->input);
		// do we clear?
		if ($clear == TRUE){
			$this->Clear();
        }
		// return output
		return $result;
	}

	/**
	* @description replace a single template variable
	* @param string $var	variable to be replaced
	* @param string $value	replacement
	* @param bool $perm		makes the change permanent [i.e. replaces input also]; defaults to FALSE
	* @return string result of replacement
	* @access public
	*/
	public function ReplaceSingle($var,$value,$perm = FALSE) {
		$var = strtoupper($var);
		if ($perm == TRUE){
			$this->input = str_replace("\{$var}",$value,$this->input);
        }
		return $this->output = str_replace("\{$var}",$value,$this->output);
	}

	/**
	* @description resets all the replaced vars to their previous status
	* @return void
	* @acces public
	*/
	public function Clear() {
		$this->output = $this->input;
	}

	/**
	* @description voids every template variable
	* @return void
	* @acces public
	*/
	public function EmptyVars() {
		return $this->output = preg_replace("'{[A-Z_\-0-9]*?}'si","",$this->output);
	}

	/**
	* @description checks if the specified template block exists
	* @param string	$block_name	block name to look for
	* @return bool TRUE if exists or FALSE if it doesnt
	* @access public
	*/
	public function BlockExists($block_name) {
		return isset($this->blocks[$block_name]) ? TRUE : FALSE;
	}
}


?>