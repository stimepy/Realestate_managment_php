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
    "amenities" => SqlPre."amenities",
    "amenity_income_cost" => SqlPre."amenity_income_cost",
    "expenses" => SqlPre."site_expenses",
    "files" => SqlPre."files",
    "files_prop" => SqlPre."files_prop",
    "files_tenant" => SqlPre."files_tenant",
    "lease_violation => lease_violation",
    "prop_unit" => SqlPre."prop_unit",
    "properties" => SqlPre."property",
    "rent_due" => SqlPre."rent_due",
    "session" => SqlPre."session",
    "tenant_site_history" => SqlPre."tenant_site_history",
    "tenants" => SqlPre."tenants",
    "users" => SqlPre."users",
    "vars" => SqlPre."vars",
);

