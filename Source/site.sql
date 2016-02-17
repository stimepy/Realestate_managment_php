SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

create database if NOT EXISTS pm;

use pm;
--
-- Database: `pm`
-- 

-- ----------------------------------------------------
-- Properties

CREATE TABLE IF NOT EXISTS `cpm_prop_property` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'property id',
  `prop_address` text NOT NULL COMMENT 'property address',
  `prop_city` varchar(200)  COMMENT 'property city',
  `prop_state` char(2)  COMMENT 'property state',
  `prop_zip` varchar(20) COMMENT 'property zip',
  `prop_country` varchar(20) NOT NULL DEFAULT 'USA' COMMENT 'property country',
  `prop_description` text NOT NULL COMMENT 'property description',
  `prop_value` decimal(10,2) COMMENT 'property value per last check',
  `prop_value_owed` decimal(10,2) COMMENT 'Loan amount left to pay',
  `loan_payment` decimal(10,2) default 0.00 comment 'How much is payed per monthly basis',
  `prop_value_sold` decimal(10,2) COMMENT 'Amount property sold for if sold',
  `prop_annual_taxes` decimal(10,2) COMMENT 'property taxes',
  `prop_insurance_cost` decimal(10,2) COMMENT 'property insurance cost',
  `prop_insurance_company` varchar(255) COMMENT 'property insurance company',-- this and cost might need another tabl
  `prop_num_units` int(11) default 1 COMMENT 'Quick reference to number of units property has',
  `active` tinyint(1) default 1 COMMENT 'Represents properties that are currently seen  0=sold, 1=inuse, 2= intransition state ',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_prop_loanpayments` (
  `paymnt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Payment',
  `prop_id` int(11) NOT NULL COMMENT 'property id',
  `paidto` Varchar(255) NOT NULL COMMENT 'property address',
  `paidto_address` text NOT NULL COMMENT 'property address',
  `paidto_city` varchar(200)  COMMENT 'property city',
  `paidto_state` char(2)  COMMENT 'property state',
  `paidto_zip` varchar(20) COMMENT 'property zip',
  `paidto_country` varchar(20) NOT NULL DEFAULT 'USA' COMMENT 'property country',
  `paid` decimal(10,2) COMMENT 'property value per last check',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`paymnt_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=1;



CREATE TABLE IF NOT EXISTS `cpm_prop_amenities` (
  `am_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'amenities id primary key',
  `description` varchar(255) DEFAULT NULL COMMENT 'Amenity description',
  `income_producer` tinyint(1) COMMENT 'Does amenity create cash (ie laundary mat) 0=no 1 = yes',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
   PRIMARY KEY (`am_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cpm_prop_amenities_unitprop` (
  `am_propunit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Uinque idenfyer for amenity',
  `am_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities',
  `prop_id` int(11) DEFAULT NULL COMMENT 'property id, connects to cpm_prop_properties',
  `unit_id` int(11) DEFAULT NULL COMMENT 'unit id connects to cpm_prop_units',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`am_propunit_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_prop_amenity_income_cost` (
  `am_ic_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier',
  `am_propunit_id` int(10) NOT NULL COMMENT 'Amenity id, connects to cpm_prop_amenities_unitprop',
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
  `unit_address` varchar(255) Comment 'Address of Unit if different then street address.',
  `unit_description` text default null COMMENT 'Unit description',
  `num_bedrooms` tinyint(3) COMMENT '# of bedrooms',
  `num_bathrooms` decimal(3,1) COMMENT '# of bathrooms',
  `sq_foot` int(11) default 0 COMMENT 'square footage',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
-- Repairs!

create table cmp_repair_connector(
  `repair_id` int(10) NOT NULL AUTO_INCREMENT primary key COMMENT 'repair id',
  `unit_id` int(10) COMMENT 'Unit id if the repair is unit specific',
  `prop_id` int(10) NOT NULL COMMENT 'Property id if the repair is for the whole property.',
  `repair_priority_id` int(10) NOT NULL COMMENT 'repair proprity id',
  `repair_cat_id` int(10) NOT NULL COMMENT 'repair proprity id',
  `repair_desc` LONGTEXT NOT NULL primary key COMMENT 'repair proprity id',
  PRIMARY KEY (`repair_id`)
)ENGINE=MyISAM  AUTO_INCREMENT=1;

create table cmp_repair_priority(
  `repair_priority_id` int(10) NOT NULL AUTO_INCREMENT primary key COMMENT 'repair proprity id',
  `priority_desc` varchar(255) NOT NULL COMMENT 'priority description',
  `priority_explntn` text NOT NULL COMMENT 'priority explained',
  `priority_order` int(10) COMMENT 'priority order',
  PRIMARY KEY (`repair_priority_id`)
)ENGINE=MyISAM  AUTO_INCREMENT=1;

insert into cmp_repair_priority (`priority_desc`, `priority_explntn`, `priority_order`)
    values('Very Low', 'It\'s an issue but no biggy, take your time. 2 months Max', 1),
    ('Low', 'Needs to be fixed but not right now. 1-2 weeks', 2),
    ('Medium', 'It\'s a problem, and needs attention sooner rather then later. 1 week or less', 3),
    ('High', 'I need this fixed as soon as you can.  1-3 days', 4),
    ('Emergancy', 'It\'s a flood/fire/???? and needs attention NOW!  Contact Landlord', 5);

Create table cmp_repair_category(
  `repair_cat_id` int(10) NOT NULL AUTO_INCREMENT primary key COMMENT 'repair proprity id',
  `cat_desc` varchar(255) NOT NULL COMMENT 'priority description'
)ENGINE=MyISAM  AUTO_INCREMENT=1 ;

Create table cmp_repair_files (
  `id`          INT(10) NOT NULL AUTO_INCREMENT,
  `repair_id`   INT(10) NOT NULL,
  `file_num_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`)
)ENGINE=MyISAM  AUTO_INCREMENT=1 ;




-- --------------------------------------------------------
-- Core

CREATE TABLE IF NOT EXISTS `cpm_core_files` (
  `file_num_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'File id',
  `file_name` varchar(255) NOT NULL default 'File Name' COMMENT 'File name',
  `file_type` varchar(20) DEFAULT NULL COMMENT 'File type (pdf, word, etc)',
  `file_created` date COMMENT 'Create date',
  `file_updated` date COMMENT 'update date',
  `file_removed` date COMMENT 'Remove date',
  PRIMARY KEY (`file_num_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



create table cmp_core_file_uploaded(
  `file_upload_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for database uploaded file',
  `file_num_id` int(10) NOT NULL COMMENT 'connects to what file id (cpm_core_files)',
  `file_mime` varchar(20) not null COMMENT 'file mime',
  `file_upload` blob COMMENT 'File data',
  `file_size` decimal (10,2) COMMENT 'file size',
  PRIMARY KEY (`file_upload_id`)
)ENGINE=MyISAM  AUTO_INCREMENT=1 ;



create table cmp_core_file_link(
  `file_link_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier with file link',
  `file_num_id` int(10) NOT NULL COMMENT 'connects to what file id (cpm_core_files)',
  `file_link` text comment 'actual file link',
  PRIMARY KEY (`file_link_id`)
)ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_core_trasnactions` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT comment 'transaction id',
  `cat_id` int(11) NOT NULL DEFAULT '0' comment 'category',
  `description` text NOT NULL comment 'description',
  `trans_date` int(11) NOT NULL DEFAULT '0' comment 'date',
  `total` decimal(12,2) NOT NULL comment 'how much',
   PRIMARY KEY (`trans_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=1;



CREATE TABLE IF NOT EXISTS `cpm_core_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Category id',
  `category_short` varchar(60) NOT NULL DEFAULt 'Name' comment 'Category Short name (ie Properties or Unit)',
  `category_description` text NOT NULL comment 'Long Description',
  `module` int(11) comment 'module link',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `cpm_core_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT comment 'Unique id for user',
  `user_name` varchar(255) NOT NULL DEFAULT '' comment 'Users name as seen',
  `user_email` varchar(200) NOT NULL DEFAULT '' comment 'Users Email',
  `user_login` varchar(100) NOT NULL comment 'Users login name',
  `user_password` varchar(255) NOT NULL comment 'users password (encrypted)',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=2;


INSERT INTO `cpm_core_users` (`user_id`, `user_name`, `user_email`, `user_login`, `user_password`) VALUES
  (1, 'Admin', 'admin@example.com', 'admin', 'test');



create table `cpm_core_modules`(
  `mod_id` int(11) NOT NULL AUTO_INCREMENT comment 'Module id',
  `mod_name` varchar(100) DEFAULT 'Mod' comment 'Module idefying name',
  `mod_path` varchar(200) DEFAULT './modules/..' comment 'where located based on directory',
  `mod_version` varchar(100) DEFAULT '1.0.0' comment 'version',
  `mod_website` varchar(100) DEFAULT '' comment 'Support Website',
  `mod_author` varchar(255) DEFAULT '' comment 'Author',
  `mod_contact` varchar(100) DEFAULT '' comment 'contact',
  `mod_active` tinyint(1) DEFAULT 0 comment 'Is mod active (0= no, 1 = yes)',
  `mod_installed` tinyint(1) DEFAULT 0 comment 'Is mod installed (0= no, 1 = yes)',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  PRIMARY KEY (`mod_id`)
) ENGINE=MyISAM   AUTO_INCREMENT=2 ;



create table `cpm_core_roles`(
  `role_id` int(11) NOT NULL AUTO_INCREMENT comment 'User role id',
  `role_name` varchar(100) DEFAULT 'foo' comment 'User role readable name',
  `role_description` text comment 'User role long description',
  `created_date` date COMMENT 'Create date',
  `updated_date` date COMMENT 'update date',
  primary key(`role_id`)
)ENGINE=MyISAM   AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_roles` (`role_id`, `role_name`, `role_description`) VALUES
  (1, 'Administrator', 'super user');



create table `cpm_core_user_roles`(
  `role_id` int(11) NOT NULL comment 'role id (connects cpm_core_roles)',
  `user_id` int(11) NOT NULL comment 'user id (connect cpm_core_users)'
)ENGINE=MyISAM   AUTO_INCREMENT=2 ;


INSERT INTO `cpm_core_user_roles` (`role_id`, `user_id`) VALUES
  (1, 1);



create table `cpm_core_user_roles_module`(
  `role_id` int(11) NOT NULL comment 'user id (connect cpm_core_users)',
  `module_id` int(11) NOT NULL comment 'module id (connect cpm_core_modules)',
  `module_disallow` text comment 'with in a module limits where they can go.'
)ENGINE=MyISAM  ;

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
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



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
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_tenants` (
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
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_files_tenant` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `file_num_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `cpm_tent_site_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `unit_id` int(10) NOT NULL,
  `lease_id` int(10) NOT NULL COMMENT 'Lease agreement for tenant',
  `screening_report_id` int(10) DEFAULT NULL COMMENT 'May have a returning tenant, want to keep this info',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

