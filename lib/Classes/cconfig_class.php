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
	public function __construct($file_name = "") {
        global $gx_library;

        //todo, remove old config system.
        $lang = "English/";
        $this->parser = new CXMLParser('config');
		if ($file_name != ""){
			$this->Load($file_name);
        }

        /**
         * New config
         */
        $gx_library->loadLibraryFile('','site_config.php', true);

        global $global_config, $config;
        $this->global_config = $global_config;
        $this-> config = $config;
        unset($global_config);
        unset($config);

        global $language;
        $this->Findloadablefiles($fileArray, "./Files/" );
        $this->Findloadablefiles($fileArray, "./Language/".$lang );
        $this->LoadFiles($fileArray);

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

    private function Findloadablefiles(&$fileArray, $path, $depth=0){
        global $language, $gx_library;

         if(is_dir($path)){
            //List out all language files
            $dir = @opendir($path);
            while(false !== ($file = readdir($dir))){
                if($file != '.' && $file != '..'){
                    if(is_dir($path.$file)){
                        $this->Findloadablefiles($fileArray, $path.$file."/", ++$depth);
                        $depth--;
                    }
                    else{
                        $filename_end=strrchr($file,".");
                        if ($filename_end==".php"){
                            $fileArray[] = $path.$file;
                        }
                    }
                }
             }
                closedir($dir);
         }
    }

    private function LoadFiles($fileArray){
        global $language, $gx_library;
        $gx_library->LoadLibraryFileArray($fileArray, true);
    }

    /**
     * @param $path
     */
    public function loadLanguage($path){

    }



}
?>