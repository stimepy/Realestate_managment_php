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
 * Class CConfig
 */
class CConfig {
    public $global_config;
    public $config;
    /**
	* current depth in xml tree
	* @var int
	* @access private
	*/
	var $depth = 0;

	/**
	* depth tags parser helper
	* @var array
	* @access private
	*/
	var $tags = array();

	/**
	* config tree
	* @var array
	* @access public
	*/
	public $vars = array();
    private $parser;

    /**
     * @description creates the xml parser and optionally loads a config file
     * @param string $file_name config file name to load
     * @return \CConfig
     * @access public
     */
	public function __construct($file_name = "") {
        global $gx_library;
        //todo, remove old config system.
        $this->parser = new CXMLParser('config');
		if ($file_name != ""){
			$this->Load($file_name);
        }
        $gx_library->loadLibraryFile('','site_config.php', true);
        global $global_config, $config;
        $this->global_config = $global_config;
        $this-> config = $config;
        unset($global_config);
        unset($config);
	}



	/**
	* @description load the config file and parse it
	* @param string $file_name	config filename to load
	* @return void
	* @access public
	*/
	public function Load($file_name) {
        global $gx_library;
        $this->vars = $gx_library->loadXMLFile($file_name, $this->vars, $this->parser, 'config');

        //$this->Parse(str_replace("&","[amp]",GetFileContents($file_name)));
        $this->file_names=$file_name;

        //return $this->vars;


	}
}
?>