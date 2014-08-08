<?php
/**
 * File description: Class file
 * Class: common
 * Modified by Kris Sherrerd
 * Last updated: 4/10/2014
 * Changes Copyright 2014 by Kris Sherrerd
 */


/**
 * Defines
 */
define("PMC_INIT", TRUE);


/**
 * @param $var
 * @param $value
 */
function IniSet($var, $value){
    if (function_exists('ini_set'))
    {
        return ini_set($var, $value);
    }
}

/**
* description returns an array with filename base name and the extension
* @param filemane format
* @return array
*/
function FileExt($filename) {

    //checking if the file have an extension
    if (!strstr($filename, ".")){
        return array("0"=>$filename,"1"=>"");
    }

    //peoceed to normal detection
    $filename = strrev($filename);

    $extpos = strpos($filename , ".");

    $file = strrev(substr($filename , $extpos + 1));
    $ext = strrev(substr($filename ,  0 , $extpos));

    return array("0"=>$file,"1"=>$ext);
}

/**
 * @param $source
 * @param $destination
 * @param string $name
 */
function UploadFile($source, $destination , $name ="") {
	$name = $name ? $name : basename($source);
	$name = FileExt($name);
	$name[2]= $name[0];

	$counter = 0 ;
	while (file_exists( $destination . $name[0] . "." . $name[1] )) {
		$name[0] = $name[2] . $counter;
		$counter ++;
	}

	copy($source , $destination . $name[0] . "." . $name[1] );
	@chmod($destination . $name[0] . "." . $name[1] , 0777);
}

/**
 * @param $source
 * @param $destination
 * @param $name
 */
function UploadFileFromWeb($source, $destination , $name) {
	$name = FileExt($name);
	$name[2]= $name[0];

	$counter = 0 ;
	while (file_exists( $destination . $name[0] . "." . $name[1] )) {
		$name[0] = $name[2] . $counter;
		$counter ++;
	}

	SaveFileContents($destination . $name[0] . "." . $name[1] , $source);
	@chmod($destination . $name[0] . "." . $name[1] , 0777);
}


/**
* @description returns the contents of a file in a string
* @param string $file_name	name of file to be loaded
* @return string
* @acces public
*/
function GetFileContents($file_name) {
//	if (!file_exists($file_name)) {
//		return null;
//	}
	$result = '';
	//echo "<br>:" . $file_name;
 	$file = fopen($file_name,"r");

	//checking if the file was succesfuly opened
	if (!$file){
		return null;
    }

	if (strstr($file_name,"://")){
		while (!feof($file))
			$result .= fread($file,1024);
    }
	else{
		$result = @fread($file,filesize($file_name));
    }

	fclose($file);
	return $result;
}

/**
 * @param $file_name
 * @param $content
 */
function SaveFileContents($file_name,$content) {
//	echo $file_name;
	$file = fopen($file_name,"w");
	fwrite($file,$content);
	fclose($file);
}

/**
 * @param $to
 * @param $from
 * @param $subject
 * @param $message
 * @param $to_name
 * @param $from_name
 * @return bool
 */
function SendMail($to,$from,$subject,$message,$to_name,$from_name) {	
	if ($to_name){
		$to = "$to_name <$to>";
    }
	
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text; charset=iso-8859-1\n";
	if ($from_name) {
		$headers .= "From: $from_name <$from>\n";
		$headers .=	"Reply-To: $from_name <$from>\n";
	}
	else {
		$headers .= "From: $from\n";
		$headers .=	"Reply-To: $from\n";
	}

	$headers .=	"X-Mailer: PHP/" . phpversion();

	return mail($to, $subject, $message,$headers);		
}


/**
* description
* @param
* @return
* @access
*/
function ResizeImage($source,$destination,$size) {
	if (PB_IMAGE_MAGICK == 1){
		system( PB_IMAGE_MAGICK_PATH . "convert $source -resize {$size}x{$size} $destination");
    }
	else{
		copy($source,$destination);
    }
}


/**
 * @param $array
 * @return mixed
 */
function RemoveArraySlashes($array) {
	if ($array)	{
		foreach ($array as $key => $item){
			if (is_array($item)){
				$array[$key] = RemoveArraySlashes($item);
            }
			else{
				$array[$key] = stripslashes($item);
            }
        }
    }
	return $array;
}

/**
 * @param $array
 * @return mixed
 */
function AddArraySlashes($array) {
	if ($array){
		foreach ($array as $key => $item){
			if (is_array($item)){
				$array[$key] = AddArraySlashes($item);
            }
			else{
				$array[$key] = addslashes($item);
            }
        }
    }
	return $array;
}

/**
 * @param $array
 * @return array|string
 */
function Ahtmlentities($array) {
    if (is_array($array)){
        foreach ($array as $key => $item){
            if (is_array($item)){
                $array[$key] = ahtmlentities($item);
            }
            else{
                $array[$key] = htmlentities(stripslashes($item),ENT_COMPAT);
            }
        }
    }
    else{
        return htmlentities(stripslashes($array),ENT_COMPAT);
    }

    return $array;
}

/**
 * @param $array
 * @return array|string
 */
function AStripSlasshes($array) {
	if (is_array($array)){
		foreach ($array as $key => $item){
			if (is_array($item)){
				$array[$key] = AStripSlasshes($item);
            }
			else{
				$array[$key] = stripslashes($item);
            }
        }
    }
	else{
		return stripslashes($array);
    }
	
	return $array;
}

/**
 * @param $array
 * @return mixed
 */
function Ahtml_entity_decode($array) {
	if ($array)	{
		foreach ($array as $key => $item){
			if (is_array($item)){
				$array[$key] = ahtml_entity_decode($item);
            }
			else{
				$array[$key] = html_entity_decode($item,ENT_COMPAT);
            }
        }
    }
	return $array;
}

/**
 * @param $passwordLength
 * @return string
 */
function RandomWord( $passwordLength ) {
    $password = "";
    for ($index = 1; $index <= $passwordLength; $index++) {
         // Pick random number between 1 and 62
         $randomNumber = rand(1, 62);
         // Select random character based on mapping.
         if ($randomNumber < 11)
              $password .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
         else if ($randomNumber < 37)
              $password .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
         else
              $password .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
    }
    return $password;
}

/***********************************************************************/

/**
 * @param $what
 * @param $with
 * @param $array
 * @return mixed
 */
function ArrayReplace($what , $with , $array ) {
	if ($array)	{
		foreach ($array as $key => $item){
			if (is_array($item)){
				$array[$key] = ArrayReplace($what , $with , $item);
            }
			else{
				$array[$key] = str_replace($what , $with , $item);
            }
        }
    }
	return $array;
}

/**
 * @Author: e107steved
 * @Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org), Copyright (C) 2008-2010 e107 Inc (e107.org)
 * @param $input
 * @param $key
 * @param $type
 * @param bool $base64
 * @return bool
 * @desctiption: Filers out common issues.
 */
function e107_filter($input,$key,$type,$base64=FALSE)
{
    if(is_string($input) && trim($input)=="")
    {
        return;
    }

    if(is_array($input))
    {
        return array_walk($input, 'e107_filter', $type);
    }

    if($type == "_POST" || ($type == "_SERVER" && ($key == "QUERY_STRING")))
    {
        if($type == "_POST" && ($base64 == FALSE))
        {
            $input = preg_replace("/(\[code\])(.*?)(\[\/code\])/is","",$input);
        }

        $regex = "/(document\.location|document\.write|base64_decode|chr|php_uname|fwrite|fopen|fputs|passthru|popen|proc_open|shell_exec|exec|proc_nice|proc_terminate|proc_get_status|proc_close|pfsockopen|apache_child_terminate|posix_kill|posix_mkfifo|posix_setpgid|posix_setsid|posix_setuid|phpinfo) *?\((.*) ?\;?/i";
        if(preg_match($regex,$input))
        {
            header('HTTP/1.0 400 Bad Request', true, 400);
            exit();
        }

        if(preg_match("/system *?\((.*);.*\)/i",$input))
        {
            header('HTTP/1.0 400 Bad Request', true, 400);
            exit();
        }

        $regex = "/(wget |curl -o |fetch |lwp-download|onmouse)/i";
        if(preg_match($regex,$input))
        {
            header('HTTP/1.0 400 Bad Request', true, 400);
            exit();
        }

    }

    if($type == "_SERVER")
    {
        if(($key == "QUERY_STRING") && (
                strpos(strtolower($input),"../../")!==FALSE
                || strpos(strtolower($input),"=http")!==FALSE
                || strpos(strtolower($input),strtolower("http%3A%2F%2F"))!==FALSE
                || strpos(strtolower($input),"php:")!==FALSE
                || strpos(strtolower($input),"data:")!==FALSE
                || strpos(strtolower($input),strtolower("%3Cscript"))!==FALSE
            ))
        {

            header('HTTP/1.0 400 Bad Request', true, 400);
            exit();
        }

        if(($key == "HTTP_USER_AGENT") && strpos($input,"libwww-perl")!==FALSE)
        {
            header('HTTP/1.0 400 Bad Request', true, 400);
            exit();
        }


    }

    if(strpos(str_replace('.', '', $input), '22250738585072011') !== FALSE) // php-bug 53632
    {
        header('HTTP/1.0 400 Bad Request', true, 400);
        exit();
    }

    if($base64 != TRUE)
    {
        e107_filter(base64_decode($input),$key,$type,TRUE);
    }

}


/**
 *
 * @param $var
 * @param null $default
 * @return array|null
 *
 * Original code from phpbb 3.0.10  Get the post/get variable.
 */
function GetVar($var, $default='', $mode1=1,$mode2=1){
    if ((!isset($_GET[$var]) && !isset($_POST[$var])) && (empty($_GET[$var]) && empty($_POST[$var]))){
        return (is_array($default)) ? array() : $default;
    }
    $_REQUEST[$var] = isset($_POST[$var]) ? $_POST[$var] : $_GET[$var];

    $super_global = '_REQUEST';
    if (!isset($GLOBALS[$super_global][$var]) && empty($GLOBALS[$super_global][$var])){//|| !is_array($GLOBALS[$super_global][$var])== is_array($default))
        return (is_array($default)) ? array() : $default;
    }
    $var = X1Clean($GLOBALS[$super_global][$var],$mode1,$mode2);
    $type = gettype($default);
    return $var;

}




/**
 * @description makes 100% sure that post/get information is clean
 * @param $var
 * @param $type
 * @param int $mode
 * @param int $mode2
 * @return string
 */
function X1Clean($var, $mode=1, $mode2=1){

    switch($mode){
        case 3://converts (,),' to html tags, should get rid of php code.
            $var = utf8_decode($var);
            $var = strtr($var, array('(' => '&#40;', ')' => '&#41;'));
            $var = htmlspecialchars($var, ENT_QUOTES, "UTF-8");
            $var = strip_tags($var);
            break;

        case 4:// no html tags, no quotes, just text
            $var = utf8_decode($var);
            $var = strip_tags($var);
            $var = strtr($var, array('(' => '&#40;', ')' => '&#41;', '\'' =>'&#39;'));
            $var = htmlspecialchars($var);
            $var = rtrim($var);
            break;

        case 5: //Array cleaning
            if(is_array($var)){
                $keys=array_keys($var);
                $count = 0;
                foreach($var as $item){
                    $newvar[$keys[$count]]=DispFunc::X1Clean($item);
                    $count++;
                }
                $var=$newvar;
            }
            else{//really should be an error;
                $var=X1Clean($var, $mode2);
            }
            break;

        default://remove all php and html tags and make (,),' into hmtl chartext equvalent
            $var = utf8_decode($var);//makes sure it's utf8 decoded.
            $var = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $var), ENT_COMPAT, 'UTF-8'));
            $var = strip_tags($var); //strips out all HTML and PHP tags
            $var = stripslashes($var);
            //$var = strtr($var, array('(' => '&#40;', ')' => '&#41;')); //translates () into html equivalent
            //$var = htmlspecialchars($var, ENT_QUOTES | ENT_HTML5);//takes that equvalent and makes it text
            break;
    }
    return $var;
}



?>
