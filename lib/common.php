<?php
/**
 * File description: Class file
 * Class: CXMLParser
 * Modified by Kris Sherrerd
 * Last updated: 4/10/2014
 * Changes Copyright 2014 by Kris Sherrerd
 */


/**
 * Defines
 */
define("PMC_INIT", TRUE);
define("RX_EMAIL","^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$");
define("RX_CHARS","[a-z\ ]");
define("RX_DIGITS","[0-9]");
define("RX_ALPHA","[^a-z0-9_]");
define("RX_ZIP","[0-9\-]");
define("RX_PHONE","[0-9\-\+\(\)]");

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
 * @param $what
 * @param int $pre
 * @param int $die
 */
function Debug($what,$pre = 1,$die = 0) {
	if (PB_DEBUG_EXT == 1) {
		if ($pre == 1){
			echo "<pre style=\"background-color:white;\">";
        }

		print_r($what);

		if ($pre == 1){
			echo "</pre>";
        }

		if ($die == 1){
			die;
        }
	}
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
 * @param $var
 * @param $fields
 * @param $with
 * @return mixed
 */
function FillVars($var,$fields,$with) {
	$fields = explode (",",$fields);

	foreach ($fields as $field){
		if (!$var[$field]){
			!$var[$field] = $with;
        }
    }
	return $var;
}

/**
 * @param $string
 * @param bool $strip_tags
 * @return string
 */
function CleanupString($string,$strip_tags = TRUE) {
	$string = addslashes(trim($string));

	if ($strip_tags){
		$string = strip_tags($string);
    }

	return $string;
}


/**
* description
* @param
* @return
* @access
*/
function CheckString($string,$min,$max,$regexp = "",$rx_result = FALSE) {
	if (get_magic_quotes_gpc() == 0){
		$string = CleanupString($string);
    }

	if (strlen($string) < $min){
		return 1;
    }
	elseif (($max != 0) && (strlen($string) > $max)){
		return 2;
    }
	elseif ($regexp != ""){
        if ($rx_result ==preg_match($regexp, $string)){
			return 3;
        }
    }

	return 0;
}

/**
* description
* @param
* @return
* @access
*///  FIRST_NAME:S:3:60,LAST_NAME:S...
function ValidateVars($source,$vars) {
	$vars = explode(",",$vars);

	foreach ($vars as $var) {
		list($name,$type,$min,$max) = explode(":",$var);

		switch ($type) {
			case "S":
				$type = RX_CHARS;
				$rx_result = FALSE;
			break;

			case "I":
				$type = RX_DIGITS;
				$rx_result = TRUE;
			break;

			case "E":
				$type = RX_EMAIL;
				$rx_result = FALSE;
			break;

			case "P":
				$type = RX_PHONE;
				$rx_result = TRUE;
			break;

			case "Z":
				$type = RX_ZIP;
				$rx_result = FALSE;
			break;

			case "A":
				$type = "";
			break;

			case "F":
				//experimental crap
				$type = RX_ALPHA;
				$rx_result = TRUE;
				//$source[strtolower($name)] = str_replace(" ", "" , $source[strtolower($name)] );
			break;
 
		}
		//var_dump($result);
		if (($result = CheckString($source[strtolower($name)],$min,$max,$type,$rx_result)) != 0){
			$errors[] = $name;
        }
		
	}	

	return is_array($errors) ? $errors : 0;
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
* uses microtime() to return the current unix time w/ microseconds
* @return float the current unix time in the form of seconds.microseconds
* @access public
*/
function GetMicroTime() {
	list($usec,$sec) = explode(" ",microtime());

	return (float) $usec + (float) $sec;
}

/**
 * @param $input
 * @param $from
 * @param $count
 * @return array
 */
function GetArrayPart($input,$from,$count) {
	$return = array();
	$max = count($input);

	for ($i = $from; $i < $from + $count; $i++ ){
		if ($i<$max){
			$return[] = $input[$i];
        }
    }
	return $return;	
}

/**
 * @param $htmldata
 * @param $image_path
 * @return mixed|string
 */
function ReplaceAllImagesPath($htmldata,$image_path) {
	$htmldata = stripslashes($htmldata);
	// replacing shit IE formating style
	$htmldata = str_replace("<IMG","<img",$htmldata);
	// esmth, i dont know why i'm doing
	preg_match_all("'<img.*?>'si",$htmldata,$images);

	foreach ($images[0] as $image){
		$htmldata = str_replace($image,ReplaceImagePath($image,$image_path),$htmldata);
    }
	
	return $htmldata;//implode("\n",$html_out);
}

/**
 * @param $image
 * @param $replace
 * @return string
 */
function ReplaceImagePath($image,$replace) {
	// removing tags
	$image = stripslashes($image);
	$image = str_replace("<","",$image);
	$image = str_replace(">","",$image);
	
	// exploging image in proprietes
	$image_arr = explode(" ",$image);
	for ($i = 0;$i < count($image_arr) ;$i++ ) {
		if (stristr($image_arr[$i],"src")) {
			// lets  it :]
			$image_arr[$i] = explode("=",$image_arr[$i]);
			// modifing the image path
			//  i hate doing this
			// replacing ',"
			$image_arr[$i][1] = str_replace("'","",$image_arr[$i][1]);
			$image_arr[$i][1] = str_replace("\"","",$image_arr[$i][1]);

			//getting only image name
			$image_arr[$i][1] = strrev(substr(strrev($image_arr[$i][1]),0,strpos(strrev($image_arr[$i][1]),"/")));

			// building the image back
			$image_arr[$i][1] = "\"" . $replace . $image_arr[$i][1] . "\"";
			$image_arr[$i] = implode ("=",$image_arr[$i]);
		}		
	}	
	// adding tags
	return "<" . implode(" ",$image_arr) . ">";
}

/**
 * @param $images
 * @param $path
 */
function DowloadAllImages($images,$path) {	
	foreach ($images as $image){
		@SaveFileContents($path ."/".ExtractFileNameFromPath($image),@implode("",@file($image)));
    }
}

/**
 * @param $htmldata
 * @return array
 */
function GetAllImagesPath($htmldata) {
	$htmldata = stripslashes($htmldata);
	// replacing shit IE formating style
	$htmldata = str_replace("<IMG","<img",$htmldata);
	// esmth, i dont know why i'm doing
	preg_match_all("'<img.*?>'si",$htmldata,$images);

//<?//ing edit plus

	foreach ($images[0] as $image)
		$images_path[] = GetImageName($image);
	
	return $images_path;
}

/**
 * @param $image
 * @return string
 */
function GetImagePath($image) {
	// removing tags
	$image = stripslashes($image);
	$image = str_replace("<","",$image);
	$image = str_replace(">","",$image);
	
	// exploging image in proprietes
	$image_arr = explode(" ",$image);
	for ($i = 0;$i < count($image_arr) ;$i++ ) {
		if (stristr($image_arr[$i],"src")) {
			// lets  it :]
			$image_arr[$i] = explode("=",$image_arr[$i]);
			// modifing the image path
			//  i hate doing this
			
			// replacing ',"
			$image_arr[$i][1] = str_replace("'","",$image_arr[$i][1]);
			$image_arr[$i][1] = str_replace("\"","",$image_arr[$i][1]);
	
			return strrev(substr(strrev($image_arr[$i][1]),0,strpos(strrev($image_arr[$i][1]),"/")));;
		}		
	}	
	// adding tags
	return "";
}

/**
 * @param $image
 * @return string
 */
function GetImageName($image) {
	// removing tags
	$image = stripslashes($image);
	$image = str_replace("<","",$image);
	$image = str_replace(">","",$image);
	
	// exploging image in proprietes
	$image_arr = explode(" ",$image);
	for ($i = 0;$i < count($image_arr) ;$i++ ) {
		if (stristr($image_arr[$i],"src")) {
			// lets  it :]
			$image_arr[$i] = explode("=",$image_arr[$i]);
			// modifing the image path
			//  i hate doing this
			
			// replacing ',"
			$image_arr[$i][1] = str_replace("'","",$image_arr[$i][1]);
			$image_arr[$i][1] = str_replace("\"","",$image_arr[$i][1]);

			return $image_arr[$i][1];
		}		
	}	
	// adding tags
	return "";
}

/**
 * @param $file
 * @return string
 */
function ExtractFileNameFromPath($file) {
	return basename($file);
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
 * @param $name
 * @param $value
 * @param int $indent
 * @return string
 */
function array2xml ($name, $value, $indent = 1){
    $xml = '';
    $indentstring = "\t";
    for ($i = 0; $i < $indent; $i++){
        $indentstring .= $indentstring;
    }
    if (!is_array($value)){
        $xml = $indentstring.'<'.$name.'>'.$value.'</'.$name.'>'."\n";
    }
    else{
        if($indent === 1){
            $isindex = False;
        }
        else{
            $isindex = True;
            while (list ($idxkey, $idxval) = each ($value)){
                if ($idxkey !== (int)$idxkey){
                    $isindex = False;
                }
            }
        }

        reset($value);
        while (list ($key, $val) = each ($value)){
            if($indent === 1){
                $keyname = $name;
                $nextkey = $key;
            }
            elseif($isindex){
                $keyname = $name;
                $nextkey = $name;
            }
            else{
                $keyname = $key;
                $nextkey = $key;
            }
            if (is_array($val)){
                $xml .= $indentstring.'<'.$keyname.'>'."\n";
                $xml .= array2xml ($nextkey, $val, $indent+1);
                $xml .= $indentstring.'</'.$keyname.'>'."\n";
            }
            else{
                $xml .= array2xml ($nextkey, $val, $indent);
            }
        }
    }
    return $xml;
}

/**
 * @param $file
 * @return mixed|string
 */
function GetPhpContent($file) {
	if (file_exists($file) ) {
		$data = GetFileContents($file);

		//replacing special chars in content
		$data = str_replace("<?php","",$data);
		$data = str_replace("?>","",$data);

		return $data;
	}
}

/**
* description
*
* @param
*
* @return
*
* @access
*/
function KeyArray($array,$recurse = 0 , $count = 1) {
	if (is_array($array)) {
		foreach ($array as $key => $val) {
			$array[$key]["key"] = $count ++;

			if ($recurse) {
				foreach ($array[$key] as $k => $val)
					if (is_array($val)) {
						KeyArray($array[$key][$k] , $recurse , $count);
					}													
			}			
		}		
	}

	return $count + 1;
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

/**
 * @param $file
 */
function DeleteFolder($file) {
    if (file_exists($file)) {
        chmod($file,0777);
        if (is_dir($file)) {
            $handle = opendir($file);
            while($filename = readdir($handle)) {
                if ($filename != "." && $filename != "..") {
                    DeleteFolder($file."/".$filename);
                }
            }
            closedir($handle);
            rmdir($file);
        }
        else {
            unlink($file);
        }
    }
}

/**
 * @param $array
 * @return int|string
 */
function GenerateRecordID($array) {
    $max = 0;
    if (is_array($array)) {
        foreach ($array as $key => $val){
            $max = ($key > $max ? $key : $max);
        }
        return $max + 1;
    }
    else return 1;
}


/**
 * @param $link
 * @return string
 */
function CryptLink($link) {

	if (defined("PB_CRYPT_LINKS") && (PB_CRYPT_LINKS == 1)) {

		if (stristr($link,"javascript:")) {
/*			if (stristr($link,"window.location=")) {
				$pos = strpos($link , "window.location=");
				$js = substr($link , 0 , $pos + 17 );
				$final = substr($link , $pos + 17 );
				$final = substr($final, 0, strlen($final) - 1 );

				//well done ... crypt the link now
				$url = @explode("?" , $final);

				if (!is_array($url))
					$url[0] = $final;

				$tmp = str_replace( $url[0] . "?" , "" , $final);	
				$uri = urlencode(urlencode(base64_encode(str_rot13($tmp))));
				$link = $js . $url[0] . "?" . $uri . md5($uri) . "'";
			}
*/
		}
        else {
	
			$url = @explode("?" , $link);

			if (!is_array($url)){
				$url[0] = $link;
            }

			$tmp = str_replace( $url[0] . "?" , "" , $link);	
			$uri = urlencode(urlencode(base64_encode(str_rot13($tmp))));
			$link = $url[0] . "?" . $uri . md5($uri);
		}
	}	
	
	return $link;
}

/************************************************************************/
/* SOME PREINITIALISATION CRAP*/



if (defined("PB_CRYPT_LINKS") && (PB_CRYPT_LINKS == 1) ) {
	$key = key($_GET);

	if (is_array($_GET) && (count($_GET) == 1) && ($_GET[$key] == "")) {

		$tmp = $_SERVER["QUERY_STRING"];
		//do the md5 check
		$md5 = substr($tmp , -32);
		$tmp = substr($tmp , 0 , strlen($tmp) - 32);
		
		if ($md5 != md5($tmp)) {
			die("Please dont change the links!");
		}
		
		$tmp = str_rot13(base64_decode(urldecode(urldecode($tmp))));

		$tmp_array = @explode("&" , $tmp);
		$tmp_array = is_array($tmp_array) ? $tmp_array : array($tmp);

		if (is_array($tmp_array)) {
			foreach ($tmp_array as $key => $val) {
				$tmp2 = explode("=" , $val);
				$out[$tmp2[0]] = $tmp2[1];
			}				
		}
        else {
			$tmp2 = explode("=" , $tmp);
			$out[$tmp2[0]] = $tmp2[1];
		}

		$_GET = $out;
	}	
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


?>
