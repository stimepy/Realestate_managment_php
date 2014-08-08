-- phpMyAdmin SQL Dump
-- version 3.5.2

--  Generation Time: Apr 15, 2014 at 03:26 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

use pm;
--
-- Database: `pm`
-- 

-- --------------------------------------------------------
--
-- Table structure for table `site_amenities`
--

CREATE TABLE IF NOT EXISTS `cpm_prop_amenities` (
  `am_id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `income` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`am_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_prop_amenities_unitprop` (
  `am_propunit_id` int(11) NOT NULL AUTO_INCREMENT,
  `am_id` int(10) NOT NULL,
  `prop_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`am_propunit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `site_amenity_income_cost`
--

CREATE TABLE IF NOT EXISTS `cpm_amenity_income_cost` (
  `am_ic_id` int(10) NOT NULL AUTO_INCREMENT,
  `am_id` int(10) NOT NULL,
  `cost` decimal(10,2) DEFAULT '0.00',
  `total_collected` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`am_ic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_expenses`
--

CREATE TABLE IF NOT EXISTS `cpm_expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_prop` int(11) NOT NULL DEFAULT '0',
  `expense_name` varchar(200) NOT NULL DEFAULT '',
  `expense_description` text NOT NULL,
  `expense_date` int(11) NOT NULL DEFAULT '0',
  `expense_cost` varchar(100) NOT NULL DEFAULT '',
  `expense_date_day` int(2) NOT NULL DEFAULT '0',
  `expense_date_month` int(2) NOT NULL DEFAULT '0',
  `expense_date_year` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`expense_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `site_expenses`
--

INSERT INTO `cpm_expenses` (`expense_id`, `expense_prop`, `expense_name`, `expense_description`, `expense_date`, `expense_cost`, `expense_date_day`, `expense_date_month`, `expense_date_year`) VALUES
  (1, 1, 'Cleaning', 'Dishwasher leaked, needed to clean carpet.', 1102136400, '39', 4, 12, 2004),
  (2, 1, 'Leaf Removal', 'Leaf removal by service', 1099285200, '48', 1, 11, 2004),
  (3, 1, 'Dishwasher', 'Dishwasher leaked. Bought a used one from Bob\\''s appliances.', 1101963600, '89', 2, 12, 2004);

-- --------------------------------------------------------

--
-- Table structure for table `site_files`
--

CREATE TABLE IF NOT EXISTS `cpm_core_files` (
  `file_num_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL default 'File Name',
  `file_type` varchar(20) DEFAULT NULL,
  `file_created` date,
  `file_updated` date,
  `file_removed` date,
  PRIMARY KEY (`file_num_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


create table cmp_core_file_uploaded(
  `file_upload_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_num_id` int(10) NOT NULL,
  `file_upload` blob,
  `file_size` decimal (10,2)
)ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

 create table cmp_core_file_link(
   `file_link_id` int(10) NOT NULL AUTO_INCREMENT,
   `file_num_id` int(10) NOT NULL,
   `file_link` text
 )ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table structure for table `site_lease_violation`
--

CREATE TABLE IF NOT EXISTS `cpm_lease_violation` (
  `vio_id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `vio_date` date NOT NULL,
  `description` text NOT NULL,
  `notified_tenant` tinyint(1) NOT NULL,
  PRIMARY KEY (`vio_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_property`
--

CREATE TABLE IF NOT EXISTS `cpm_prop_property` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_address` text NOT NULL,
  `prop_city` varchar(200) NOT NULL DEFAULT '',
  `prop_state` char(2) NOT NULL DEFAULT '',
  `prop_zip` varchar(20) NOT NULL DEFAULT '',
  `prop_description` text NOT NULL,
  `prop_leased_amount` varchar(100) NOT NULL DEFAULT '',
  `prop_value` decimal(10,2),
  `prop_value_owed` decimal(10,2),
  `prop_value_sold` decimal(10,2),
  `prop_value_annual_taxes` decimal(10,2),
  `prop_insurance_cost` decimal(10,2),
  `prop_insurance_company` varchar(255),
  `prop_num_units` int(11),
  `created_date` date,
  `updated_date` date,
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `site_prop_files`
--

CREATE TABLE IF NOT EXISTS `cpm_prop_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `prop_id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `file_num_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_prop_unit`
--

CREATE TABLE IF NOT EXISTS `cpm_prop_unit` (
  `unit_id` int(10) NOT NULL AUTO_INCREMENT,
  `prop_id` int(10) NOT NULL,
  `amenities` text,
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_rent_due`
--

CREATE TABLE IF NOT EXISTS `cpm_rent_due` (
  `rent_id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `unit_id` int(10) NOT NULL,
  `total_util` decimal(10,2) NOT NULL,
  `total_rent` decimal(10,2) NOT NULL,
  `total_payed` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `date_payed` date DEFAULT NULL,
  PRIMARY KEY (`rent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_tenants`
--

CREATE TABLE IF NOT EXISTS `cpm_tenants` (
  `tenant_id` int(10) NOT NULL AUTO_INCREMENT,
  `cur_prop_id` int(11) NOT NULL,
  `cur_unit_id` int(10) NOT NULL,
  `screening_report_id` int(10) DEFAULT NULL,
  `latest_rent_id` int(10) DEFAULT NULL,
  `First_Name` varchar(100) NOT NULL DEFAULT 'na',
  `Middle_Name` varchar(100) DEFAULT NULL,
  `Last_Name` varchar(100) NOT NULL DEFAULT 'na',
  `user_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `primary_telephone` int(10) DEFAULT NULL,
  `secondary_telephone` int(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `lengthoflease` int(10) DEFAULT '0',
  `renewablelease` tinyint(1) DEFAULT '0',
  `renewablelength` int(10) DEFAULT '0',
  `lease_violations` int(10) DEFAULT '0',
  `current_rent` decimal(10,2) DEFAULT '0.00',
  `move_in_date` date DEFAULT NULL,
  `move_out_date` date DEFAULT NULL,
  `rent_late_days` int(10) DEFAULT '0',
  `previous_mailing_address` text,
  `current_mailing_address` text,
  `fowarding_address` text,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`tenant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_tenant_files`
--

CREATE TABLE IF NOT EXISTS `cpm_files_tenant` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `file_num_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_tenant_site_history`
--

CREATE TABLE IF NOT EXISTS `cpm_tenant_site_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `unit_id` int(10) NOT NULL,
  `lease_id` int(10) NOT NULL COMMENT 'Lease agreement for tenant',
  `screening_report_id` int(10) DEFAULT NULL COMMENT 'May have a returning tenant, want to keep this info',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_users`
--

CREATE TABLE IF NOT EXISTS `cpm_core_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `user_email` varchar(200) NOT NULL DEFAULT '',
  `user_login` varchar(100) NOT NULL DEFAULT '',
  `user_password` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_users`
--

INSERT INTO `cpm_core_users` (`user_id`, `user_name`, `user_email`, `user_login`, `user_password`) VALUES
  (1, 'Admin', 'admin@example.com', 'admin', 'test');

-- --------------------------------------------------------

create table `cpm_core_modules`(
  `mod_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod_name` varchar(100) DEFAULT 'Mod',
  `mod_path` varchar(200) DEFAULT './modules/..',
  `mod_version` varchar(100) DEFAULT '1.0.0',
  `mod_website` varchar(100) DEFAULT '',
  `mod_active` tinyint(1) DEFAULT 0,
  `mod_installed` tinyint(1) DEFAULT 0,
  `mod_user_restricted` text,
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


create table `cpm_core_roles`(
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) DEFAULT 'foo',
  `role_description` varchar(255) DEFAULT 'for the foo',
  primary key(`role_id`)
)ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_roles` (`role_id`, `role_name`, `role_description`) VALUES
  (1, 'Administrator', 'super user');

create table `cpm_core_user_roles`(
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
)ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_roles` (`role_id`, `user_id`) VALUES
  (1, 1);
