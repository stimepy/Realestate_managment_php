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

-- ----------------------------------------------------
-- Properties

CREATE TABLE IF NOT EXISTS `cpm_prop_property` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'property id',
  `prop_address` text NOT NULL COMMENT 'property address',
  `prop_city` varchar(200) NOT NULL DEFAULT '' COMMENT 'property city',
  `prop_state` char(2) NOT NULL DEFAULT '' COMMENT 'property state',
  `prop_zip` varchar(20) NOT NULL DEFAULT '' COMMENT 'property zip',
  `prop_country` varchar(20) NOT NULL DEFAULT 'USA' COMMENT 'property country',
  `prop_description` text NOT NULL COMMENT 'property description',
  `prop_value` decimal(10,2) COMMENT 'property value per last check',
  `prop_value_owed` decimal(10,2)   `prop_value` decimal(10,2) COMMENT 'Loan amount left to pay',
  'loan_payment' decimal(10,2) default 0.00 comment 'How much is payed per monthly basis',
  `prop_value_sold` decimal(10,2) COMMENT 'Amount property sold for if sold',
  `prop_annual_taxes` decimal(10,2) COMMENT 'property taxes',
  `prop_insurance_cost` decimal(10,2) COMMENT 'property insurance cost',
  `prop_insurance_company` varchar(255) COMMENT 'property insurance company',-- this and cost might need another tabl
  `prop_num_units` int(11) default 1 COMMENT 'Quick reference to number of units property has',
  `active` tinyint(1) default 1 COMMENT 'Represents properties that are currently seen  0=sold, 1=inuse, 2= intransition state ',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cpm_prop_amenities` (
  `am_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'amenities id primary key',
  `description` varchar(255) DEFAULT NULL COMMENT 'Amenity description',
  `income_producer` tinyint(1) COMMENT 'Does amenity create cash (ie laundary mat) 0=no 1 = yes',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
   PRIMARY KEY (`am_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cpm_prop_amenities_unitprop` (
  `am_propunit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Uinque idenfyer for amenity',
  `am_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities',
  `prop_id` int(11) DEFAULT NULL COMMENT 'property id, connects to cpm_prop_properties',
  `unit_id` int(11) DEFAULT NULL COMMENT 'unit id connects to cpm_prop_units',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`am_propunit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_prop_amenity_income_cost` (
  `am_ic_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `am_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities',
  `cred_deb_id` int(11) not null comment 'Connects to income or expense core table based on bred_deb',
  `creddeb` tinyint(1) COMMENT 'Credit or Debit (ie did it cost me or was did income come from it) 0 = credit/income, 1 = debit/expense',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`am_ic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



CREATE TABLE IF NOT EXISTS `cpm_prop_files` (
  `prop_file_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `prop_id` int(11) NOT NULL COMMENT 'Property the file is tied to (cpm_prop_properties)',
  `unit_id` int(11) DEFAULT NULL COMMENT 'Unit file is tied to (cpm_prop_unit)',
  `file_num_id` int(10) NOT NULL COMMENT 'File I am connecting to (cpm_core_files)',
  PRIMARY KEY (`prop_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `cpm_core_files` (
  `file_num_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'File id',
  `file_name` varchar(255) NOT NULL default 'File Name' COMMENT 'File name',
  `file_type` varchar(20) DEFAULT NULL COMMENT 'File type (pdf, word, etc)',
  `file_created` date COMMENT 'Create date',
  `file_updated` date COMMENT 'update date',
  `file_removed` date COMMENT 'Remove date',
  PRIMARY KEY (`file_num_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



create table cmp_core_file_uploaded(
  `file_upload_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for database uploaded file',
  `file_num_id` int(10) NOT NULL COMMENT 'connects to what file id (cpm_core_files)',
  `file_mime` varchar(20) not null COMMENT 'file mime',
  `file_upload` blob COMMENT 'File data',
  `file_size` decimal (10,2) COMMENT 'file size',
  PRIMARY KEY (`file_upload_id`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



create table cmp_core_file_link(
  `file_link_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier with file link',
  `file_num_id` int(10) NOT NULL COMMENT 'connects to what file id (cpm_core_files)',
  `file_link` text comment 'actual file link',
  PRIMARY KEY (`file_upload_id`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_core_expenses` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT comment 'expense id',
  `expense_cat` int(11) NOT NULL DEFAULT '0' comment 'expense category',
  `expense_description` text NOT NULL comment 'expense description',
  `expense_date_paid` int(11) NOT NULL DEFAULT '0' comment 'date expense was paid',
  `expense_cost` decimal(12,2) NOT NULL comment 'how much was paid',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`expense_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cpm_core_income` (
  `income_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Income id',
  `income_cat` int(11) NOT NULL DEFAULT '0' COMMENT 'income category',
  `income_description` text NOT NULL COMMENT 'income description',
  `income_date_paid` int(11) NOT NULL DEFAULT '0' COMMENT 'date income was received',
  `income_paid` decimal(12,2) NOT NULL COMMENT 'how much was received',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`income_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cpm_core_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category id',
  `category_short` varchar(60) NOT NULL DEFAULt 'Name' comment 'Category Short name (ie Properties or Unit)',
  `category_description` text NOT NULL comment 'Long Description',
  `module` int(11) comment 'module link',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cpm_core_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT comment 'Unique id for user',
  `user_name` varchar(255) NOT NULL DEFAULT '' comment 'Users name as seen',
  `user_email` varchar(200) NOT NULL DEFAULT '' comment 'Users Email',
  `user_login` varchar(100) NOT NULL comment 'Users login name',
  `user_password` varchar(255) NOT NULL comment 'users password (encrypted)',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;


INSERT INTO `cpm_core_users` (`user_id`, `user_name`, `user_email`, `user_login`, `user_password`) VALUES
  (1, 'Admin', 'admin@example.com', 'admin', 'test');



create table `cpm_core_modules`(
  `mod_id` int(11) NOT NULL AUTO_INCREMENT comment 'Module id',
  `mod_name` varchar(100) DEFAULT 'Mod' comment 'Module idefying name',
  `mod_path` varchar(200) DEFAULT './modules/..' comment 'where located based on directory',
  `mod_version` varchar(100) DEFAULT '1.0.0' comment 'version',
  `mod_website` varchar(100) DEFAULT '' comment 'Support Website',
  `mod_active` tinyint(1) DEFAULT 0 comment 'Is mod active (0= no, 1 = yes)',
  `mod_installed` tinyint(1) DEFAULT 0 comment 'Is mod installed',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;



create table `cpm_core_roles`(
  `role_id` int(11) NOT NULL AUTO_INCREMENT comment 'User role id',
  `role_name` varchar(100) DEFAULT 'foo' comment 'User role readable name',
  `role_description` text DEFAULT 'for the foo' comment 'User role long description',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  primary key(`role_id`)
)ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_roles` (`role_id`, `role_name`, `role_description`) VALUES
  (1, 'Administrator', 'super user');



create table `cpm_core_user_roles`(
  `role_id` int(11) NOT NULL comment 'role id (connects cpm_core_roles)',
  `user_id` int(11) NOT NULL comment 'user id (connect cpm_core_users)'
)ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_roles` (`role_id`, `user_id`) VALUES
  (1, 1);


-- --------------------------------------------------------
-- tenents for rent

CREATE TABLE IF NOT EXISTS `cpm_tent_lease_violation` (
  `vio_id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `vio_date` date NOT NULL,
  `description` text NOT NULL,
  `notified_tenant` tinyint(1) NOT NULL,
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`vio_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_rent` (
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



CREATE TABLE IF NOT EXISTS `cpm_tent tenants` (
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
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`tenant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_files_tenant` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `file_num_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_site_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `unit_id` int(10) NOT NULL,
  `lease_id` int(10) NOT NULL COMMENT 'Lease agreement for tenant',
  `screening_report_id` int(10) DEFAULT NULL COMMENT 'May have a returning tenant, want to keep this info',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;