<?php
/**
 * Capital Property Management System
 *
 * File: site_config.php
 *
 * The configuation file for the entire site.  Changes madee here may break something so beware
 */
if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
global $global_config, $config;

$global_config = Array (
    "default_location" => "index.php",
    "language" => "English",
    "version" => "0.0.01",
	);


$config = Array(
    /**
     * Paths, forms.
     */
    "paths" => Array(
        "root" => "./",
        "backpath" => "../",
        "formspath" => "./forms/",
        "templatepath" => "./templates/",
        "imagespath" => "./images/",
        "modulepath" => "./module/",
        "libpath"  => "./lib/",
        "buttonpath" => "./image/button/",
        "class" => "Classes/",
        "admin" => "admin/",
        "user" => "user/",
        "expenses" => "expenses/",
        "properties" => "properties/",
        "users" => "users/",
    ),
    "forms" => Array (
        "form" => "form.htm",
        "layout" => "layout.htm",
        "login" => "login.htm",
        "add" => "add.xml",
        "details" => "details.xml",
        "list" => "list.xml",
    ),

    /**
     * Database information
     */
    "database" => array(
        "database_type" => "mysql",
        "server" => "localhost",
        "login" => "root",
        "password" => "",
        "default" => "pm",
   ),

);

define("SqlPre", "CPM_");

?>