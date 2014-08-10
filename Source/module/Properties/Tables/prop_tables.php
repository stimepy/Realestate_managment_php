<?php
/**
 * Capital Property Management System
 *
 * File: prop_tables.php
 * Author: Kris Sherrerd
 * Copyright: 2014
 * Version 0.1
 * Modified: 8/10/2014
 * Definition of the SQL tables
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
global $language;

$language["tables"] = Array(
    'amenities' => SqlPre.'cpm_prop_amenities',
    'am_unitprop' => SqlPre.'cpm_prop_amenities_unitprop',
    'property' =>SqlPre.'cpm_prop_property',
    'prop_files' => SqlPre.'cpm_prop_files',
    'unit_files' => SqlPre.'cpm_prop_unit',
    'amenity_income' => SqlPre.'cpm_prop_amenity_income_cost',
);

?>