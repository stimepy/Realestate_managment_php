<?php
/**
 * Capital Property Management System
 *
 * File: site_config.php
 *
 * The configuation file for the entire site.  Changes made here may break something so beware
 */
if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

global $global_config, $config;

$global_config = Array (
    "default_location" => "index.php",
    "language" => "English",
    "version" => "0.0.01",
    "theme" => "Alpha",
	);


$config = Array(
    /**
     * Paths, oldforms.
     */
    "paths" => Array(
        "root" => "./",
        "backpath" => "../",
        "formspath" => "./oldforms/",
        "themepath" => "./themes/",
        "imagespath" => "./images/",
        "modulepath" => "./module/",
        "libpath"  => "./lib/",
        "buttonpath" => "./image/button/",
        "class" => "Classes/",
        "admin" => "admin/",
        "users" => "users/",
        "vendor" => "./vendor/",

    ),


    /**
     * Database information
     */
    "database" => array(
        "database_type" => "mysql",
        "server" => "localhost",
        "login" => "root",
        "password" => "",
        "default" => "pmc",
   ),

);

define("SqlPre", "cpm_");
define("TEMPLATE_HOLD", 1);
define("TEMPLATE_SHOW", 0);
define("TEMPLATE_RETURN", 2);

?>