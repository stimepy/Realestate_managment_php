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
     * Basic constructor, loads base configs
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
     * Loads the config file, as stated by the file path
     * @param string $path
     * @param -1 $maxdepth
     */
    private function LoadConfigFiles($path, $maxdepth=-1){
        global $gx_library;
        $gx_library->Findloadablefiles($fileArray, $path, $maxdepth);
        $gx_library->LoadLibraryFileArray($fileArray, true);
    }

    /**
     * @description allows modules that have seperate language to load their language to $gx_config->language
     * @param string $path
     * @param -1 $maxdepth
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