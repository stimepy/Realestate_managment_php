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



$place = GetVar('prop', '')

switch($place){
    default:
        propertyOverview();
        break;
}


function propertyOverview(){
    global $gx_db, $gx_config, $gx_user;

    if(!){

    }

}



?>