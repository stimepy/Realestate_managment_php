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

    public function LoadLibraryFileArray($files, $required = false){

        if(!is_array($files)){
            //return error
        }
        for($i=0; $i<sizeof($files); $i++){
           $temp_lngth = strrpos($files[$i], '/');

           $this->myFileIncludes(substr ( $files[$i] ,0 , $temp_lngth),substr ( $files[$i] ,$temp_lngth , strlen($files[$i])- $temp_lngth ),$required);

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

    /**
     * @description goes into a directory and returns all files in an array.
     * @param array(strings) &$fileArray
     * @param string $path
     * @param int $max=-1
     * @param int $depth =0
     *
     */
    public function Findloadablefiles(&$fileArray, $path, $max=-1, $depth=0){
        if(($depth == $max)){
            return;
        }
        if(is_dir($path)){
            //List out all language files
            $dir = @opendir($path);
            while(false !== ($file = readdir($dir))){
                if($file != '.' && $file != '..'){
                    if(is_dir($path.$file)){
                        $this->Findloadablefiles($fileArray, $path.$file."/", $max, ++$depth);
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
    public function FindNamedFiles(&$fileNamePath, $path, $named_files, $nocheck="", $max=-1, $depth = 0 ){
        if(($depth == $max)){
            return;
        }
        if(is_dir($path)){
            //List out all language files
            $dir = @opendir($path);
            while(false !== ($file = readdir($dir))){
                if($file != '.' && $file != '..'){
                    if(is_dir($path.$file)){
                        if(!$this->DirNoGo($file, $nocheck)){
                            $this->Findloadablefiles($fileArray, $path.$file."/", $max, ++$depth);
                            $depth--;
                        }
                    }
                    else{
                        if(is_array($named_files)){
                            for($i=0; $i<sizeof($named_files); $i++){
                                if(strcasecmp ( $named_files[i] , $file ) == 0){
                                    $fileNamePath[] = $path.$file;
                                }
                            }
                        }
                        elseif(strcasecmp ( $named_files[i] , $file ) == 0){
                            $fileNamePath[] = $path.$file;
                        }
                    }
                }
            }
            closedir($dir);
        }
    }

    private function DirNoGo($to, $nono){
        if(is_array($nono)){
            for($i=0; $i<sizeof($nono); $i++){
                if(strcasecmp ( $nono[i] , $to ) == 0 ){
                    return true;
                }
            }
        }
        elseif(strcasecmp( $nono , $to ) == 0){
            return true;
        }
        return false;
    }
}
?>