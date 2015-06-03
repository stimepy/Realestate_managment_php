<?php
/**
 * Capital Property Management System
 *
 * File: cpm_tables.php
 * Author: Kris Sherrerd
 * Copyright: 2014
 * Version 0.1
 * Modified: 4/13/2014
 * Definition of the SQL tables
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
global $language;

$language["tables"] = Array(
    //core tables
    "user_role" => SqlPre."core_user_roles",
    "roles" => SqlPre."core_roles",
    "module" => SqlPre."core_modules",
    "users" => SqlPre."core_users",
    "files" => SqlPre."core_files",
    "files_uploaded" => SqlPre."core_file_uploaded",
    "files_link" => SqlPre."core_file_link",
    "expenses" => SqlPre."core_expenses",

);

