<?php
/**
 * User: ksherrerd
 * Filename: pmfig.php
 * last Modified: 4/10/14
 * Version: 1.1
 */
error_reporting(E_ALL);


define("_LIBPATH","./lib/");
//define("_CLASSPATH", "./lib/Classes/");
/* load most common functions first*/
require_once(_LIBPATH."Classes/CLibrary_class.php");
$gx_library = new CLibrary();
$gx_library->loadLibraryFile(_LIBPATH,"common.php");


// Block common bad agents / queries / php issues.
array_walk($_SERVER,  'e107_filter', '_SERVER');
if (isset($_GET)) array_walk($_GET,     'e107_filter', '_GET');
if (isset($_POST)) array_walk($_POST,    'e107_filter', '_POST');
if (isset($_COOKIE)) array_walk($_COOKIE,  'e107_filter', '_COOKIE');

//
//  Remove all output buffering
//
while (@ob_end_clean());  // destroy all ouput buffering
ob_start();             // start our own.
$oblev_at_start = ob_get_level(); 	// preserve when destroying globals in step C

//
//  Find out if register globals is enabled and destroy them if so
// (DO NOT use the value of any variables before this point! They could have been set by the user)
//

if(function_exists('ini_get')) {
    $register_globals = ini_get('register_globals');
}
else{
    $register_globals = true;
}

if($register_globals == true){
    while (list($global) = each($GLOBALS)) {
        if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*|_REQUEST|retrieve_prefs|eplug_admin|eTimingStart)|oblev_.*$/', $global)) {
            unset($$global);
        }
    }
    unset($global);
}

if(($pos = strpos(strtolower($_SERVER['PHP_SELF']), ".php/")) !== false) // redirect bad URLs to the correct one.
{
    $new_url = substr($_SERVER['PHP_SELF'], 0, $pos+4);
    $new_loc = ($_SERVER['QUERY_STRING']) ? $new_url."?".$_SERVER['QUERY_STRING'] : $new_url;
    header("Location: ".$new_loc);
    exit();
}
$_SERVER['PHP_SELF'] = (($pos = strpos(strtolower($_SERVER['PHP_SELF']), ".php")) !== false ? substr($_SERVER['PHP_SELF'], 0, $pos+4) : $_SERVER['PHP_SELF']);
unset($pos);


global  $site;


$files = array("CUsers_class.php", "CTemplate_class.php", "cconfig_class.php", "CDatabase_class.php",  "CMaster_Class.php", "CSessions_class.php", "Modules_Class.php");


/* load the rest of the files that are needed. */
$gx_library->loadLibraryFile(_LIBPATH."Classes/",$files,$required = true);

$gx_library->loadLibraryFile(_LIBPATH,["mail.php", 'Template_func.php'],$required = false);



?>
