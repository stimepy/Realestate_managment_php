<?php
/**
 * File description: Class file
 * Class: CXMLParser
 * Modified by Kris Sherrerd
 * Last updated: 4/10/2014
 * Changes Copyright 2014 by Kris Sherrerd
 */



class CLibrary {
	/**
	* unique library identifier
	* @var string
	* @access private
	*/
	private $library_name = Array();
    private $file_names = Array();

	/**
	* constructor which sets the lib`s name
	* @param string $name	unique library identifier
	* @return void
	* @acces public
	*/
	function _construct() {
	}

    /**
     * @param $filepath
     * @param $filename
     * @param bool $required
     * @return bool
     */
    function loadLibraryFile($filepath, $filename, $required = false){
        if(!$filename || !$filepath){
            //return error
        }
        if(is_array($filename)){
            for($i=0; $i<sizeof($filename); $i++){
                $this->myFileIncludes($filepath,$filename[$i],$required);
                $this->file_names[] = $filename;
            }
        }
        else{
            $this->myFileIncludes($filepath,$filename,$required);
            $this->file_names[] = $filename;
        }
        return true;
    }

    /**
     * @param $filepath
     * @param $filename
     * @param $required
     * @return bool
     */
    private function myFileIncludes($filepath,$filename,$required){
        if(!$required){
            if(include_once($filepath.$filename)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            require_once($filepath.$filename);
        }
        return true;
    }

    /**
     * @param $file_name
     * @param $vars
     * @return mixed
     */
    function loadXMLFile($file_name, $vars, $parser, $type = null){
       //global ;
        $vars = $parser->Parse(str_replace("&","[amp]",GetFileContents($file_name)),$return_type = true, $type);
        $parser->freeParser();
        $this->library_name[]=$file_name;

        return  ArrayReplace("[amp]" , "&" , $vars );
    }

    function loadHtmFile($source){
        if(file_exists($source)){
            $this->library_name[]=$source;
            return GetFileContents($source);
        }
        else{
            //error
        }

    }
}
?>