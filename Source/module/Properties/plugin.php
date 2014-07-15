<?php
/**
 * Created by PhpStorm.
 * User: stimepy
 * Date: 4/21/14
 * Time: 7:56 PM
 */

if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}

if(!installed()){
    //
  $sql = "CREATE TABLE IF NOT EXISTS `cpm_property` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_address` text NOT NULL,
  `prop_city` varchar(200) NOT NULL DEFAULT '',
  `prop_state` char(2) NOT NULL DEFAULT '',
  `prop_zip` varchar(20) NOT NULL DEFAULT '',
  `prop_description` text NOT NULL,
  `prop_total_leased_amount` varchar(100) NOT NULL DEFAULT '',
  `prop_value` decimal(10,2),
  `prop_last_value_date` Date not null,
  `prop_total_units`,
  `prop_open_units`,
  PRIMARY KEY (`prop_id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";



}