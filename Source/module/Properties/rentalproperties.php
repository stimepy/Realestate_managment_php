<?php
/**
 * File description: plugin
 * @author Kris Sherrerd  stimepy@aodhome.com
 * Modified by Kris Sherrerd
 * Last updated: 8/7/2014
 * Copyright (c) 2014
 * Version 1.0
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}
if(!$gx_users->permission('properties')){
    //redirect to an error page
}



$place = GetVar('prop', '');

switch($place){
    default:
        propertyOverview();
        break;
}


function propertyOverview(){
    global $gx_db, $gx_config, $gx_user;
    if(!$gx_user->checkloggedin()){
        $gx_user->GoLogin();
    }
    $sqlselects ='cpp.prop_id, prop_address, prop_city, prop_state, prop_zip, prop_description, prop_value, prop_value_owed, loan_payment,   prop_annual_taxes, prop_num_units, active , tntcnt';
    $table = $gx_config->language['tables']['prop_property'] .'
    left join (select cur_prop_id prop_id, sum(active) tntcnt from '. $gx_config->language['tables']['tent_tenants'] .')ten_cnt
    using(prop_id)';

    $results = $gx_db->QuerySelect($table,$sqlselects);
}



?>