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
    public $language;
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
	public function __construct() {
        global $gx_library;
        //load configuration files etc.
        $this->LoadConfigFiles("./Files/");
        global $global_config, $config, $language;
        $this->global_config = $global_config;
        $this-> config = $config;
        unset($global_config);
        unset($config);
        //Load languages
        $this->LoadConfigFiles("./Language/".$this->global_config['language']."/");
        $this->language = $language;
        unset($language);
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

	}



    private function LoadConfigFiles($path, $maxdepth=-1){
        global $gx_library;
        $gx_library->Findloadablefiles($fileArray, $path, $maxdepth);
        $gx_library->LoadLibraryFileArray($fileArray, true);
    }

    /**
     * @description allows modules that have seperate language to load their language to $gx_config->language
     * @param $path
     * @param $maxdepth
     * @return bool
     */
    public function LoadMoreLanguage($path, $maxdepth=-1){
        global $language;
        $this->loadConfigFiles($path, $maxdepth);
        $this->language = array_merge($language, $this->language);
        unset($language);
        return true;
    }



}
?>