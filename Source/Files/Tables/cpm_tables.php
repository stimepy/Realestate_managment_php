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
    "user_role" => SqlPre."cpm_core_user_roles",
    "roles" => SqlPre."cpm_core_roles",
    "module" => SqlPre."cpm_core_modules",
    "users" => SqlPre."cpm_core_users",
    "files" => SqlPre."cpm_core_files",
    "files_uploaded" => SqlPre."cmp_core_file_uploaded",
    "files_link" => SqlPre."cmp_core_file_link",
    "expenses" => SqlPre."cpm_core_expenses",

);

