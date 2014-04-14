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
    "upload" => "./upload/",
    "path" => "../",
    "url" => "../",
	"database_type" => "mysql",
    "templates"=> Array("path" =>"./templates/",
        "admin_path" => "./templates/admin/",
    ),
    "version" => "0.0.1"
	);


$config = Array(
    /**
     * Paths, forms.
     */
    "formspath" => "./forms/",
    "default_location" => "index.php",
    "forms" => Array ("adminpath" => "./forms/admin/",
        "userpath" => "./forms/user/",
        "admintemplate" => "./templates/form.htm",
        "formpath" => "./forms/",
        "sitetemplate" => "./admin/templates/form.htm",
    ),

    /**
     * Database information
     */
    "database" => array(
       "server" => "localhost",
        "login" => "root",
        "password" => "",
        "default" => "pm",
   ),

    /**
     * Database tables
     */
    "tables" => Array(
        "properties" => "site_property",
	    "expenses" => "site_expenses",
	    "users" => "site_users",
	    "vars" => "site_vars",
    ),

    /**
     * Templates
     */
    "templates" => Array (
        "admin_login" => "login.htm",
	    "admin_layout" => "layout.htm",
    ),

);

?>