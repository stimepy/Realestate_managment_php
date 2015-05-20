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

function installed(){
    global $gx_module;
    return $gx_module->isInstalled('Properties');

}

function uninstall(){
    // return error... for now....
}

if(!installed()){
    //return error
    /* If there were a non-system module call installModule*/
}


function installModule(){
    $tables = array('prop_property' => array('`prop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'property id\'',
        '`prop_address` text NOT NULL COMMENT \'property address\'',
        '`prop_city` varchar(200) COMMENT \'property city\'',
        '`prop_state` char(2) COMMENT \'property state\'',
        '`prop_zip` varchar(20) COMMENT \'property zip\'',
        '`prop_country` varchar(20) NOT NULL DEFAULT \'USA\' COMMENT \'property country\'',
        '`prop_description` text NOT NULL COMMENT \'property description\'',
        '`prop_value` decimal(10,2) COMMENT \'property value per last check\'',
        '`prop_value_owed` decimal(10,2) COMMENT \'Loan amount left to pay\'',
        '`loan_payment` decimal(10,2) default 0.00 comment \'How much is payed per monthly basis\'',
        '`prop_value_sold` decimal(10,2) COMMENT \'Amount property sold for if sold\'',
        '`prop_annual_taxes` decimal(10,2) COMMENT \'property taxes\'',
        '`prop_insurance_cost` decimal(10,2) COMMENT \'property insurance cost\'',
        '`prop_insurance_company` varchar(255) COMMENT \'property insurance company\'',
        '`prop_num_units` int(11) default 1 COMMENT \'Quick reference to number of units property has\'',
        '`active` tinyint(1) default 1 COMMENT \'Represents properties that are currently seen  0=sold, 1=inuse, 2= intransition state \'',
        '`created_date` date COMMENT \'Create date\'',
        '`updated_date` date COMMENT \'update date\'',
        'PRIMARY KEY (`prop_id`)')
        ,'prop_amenities' => array("`am_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'amenities id primary key'",
        "`description` varchar(255) DEFAULT NULL COMMENT 'Amenity description'",
        "`income_producer` tinyint(1) COMMENT 'Does amenity create cash (ie laundary mat) 0=no 1 = yes'",
        "`created_date` date COMMENT 'Create date'",
        "`updated_date` date COMMENT 'update date'",
        "PRIMARY KEY (`am_id`)")
        ,"prop_amenities_unitprop" => array("`am_propunit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Uinque idenfyer for amenity'",
        "`am_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities'",
        "`prop_id` int(11) DEFAULT NULL COMMENT 'property id, connects to cpm_prop_properties'",
        "`unit_id` int(11) DEFAULT NULL COMMENT 'unit id connects to cpm_prop_units'",
        "`created_date` date COMMENT 'Create date'",
        "`updated_date` date COMMENT 'update date'",
        "PRIMARY KEY (`am_propunit_id`)")
        ,
);


CREATE TABLE IF NOT EXISTS `cpm_prop_amenity_income_cost` (
    `am_ic_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `am_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities',
  `cred_deb_id` int(11) not null comment 'Connects to income or expense core table based on bred_deb',
  `creddeb` tinyint(1) COMMENT 'Credit or Debit (ie did it cost me or was did income come from it) 0 = credit/income, 1 = debit/expense',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`am_ic_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1;



CREATE TABLE IF NOT EXISTS `cpm_prop_files` (
    `prop_file_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `prop_id` int(11) NOT NULL COMMENT 'Property the file is tied to (cpm_prop_properties)',
  `unit_id` int(11) DEFAULT NULL COMMENT 'Unit file is tied to (cpm_prop_unit)',
  `file_num_id` int(10) NOT NULL COMMENT 'File I am connecting to (cpm_core_files)',
  PRIMARY KEY (`prop_file_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_prop_unit` (
    `unit_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unit id',
  `prop_id` int(10) NOT NULL COMMENT 'Property unit is tied to (cpm_prop_properties)',
  `unit_description` text default null COMMENT 'Unit description',
  `num_bedrooms` tinyint(3) COMMENT '# of bedrooms',
  `num_bathrooms` decimal(3,1) COMMENT '# of bathrooms',
  `sq_foot` int(11) default 0 COMMENT 'square footage',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

}
?>