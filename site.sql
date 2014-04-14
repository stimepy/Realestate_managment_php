-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Database: `property`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `site_expenses`
-- 

CREATE TABLE `site_expenses` (
  `expense_id` int(11) NOT NULL auto_increment,
  `expense_prop` int(11) NOT NULL default '0',
  `expense_name` varchar(200) NOT NULL default '',
  `expense_description` text NOT NULL,
  `expense_date` int(11) NOT NULL default '0',
  `expense_cost` varchar(100) NOT NULL default '',
  `expense_date_day` int(2) NOT NULL default '0',
  `expense_date_month` int(2) NOT NULL default '0',
  `expense_date_year` int(4) NOT NULL default '0',
  PRIMARY KEY  (`expense_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `site_expenses`
-- 

INSERT INTO `site_expenses` VALUES (1, 1, 'Cleaning', 'Dishwasher leaked, needed to clean carpet.', 1102136400, '39', 4, 12, 2004);
INSERT INTO `site_expenses` VALUES (2, 1, 'Leaf Removal', 'Leaf removal by service', 1099285200, '48', 1, 11, 2004);
INSERT INTO `site_expenses` VALUES (3, 1, 'Dishwasher', 'Dishwasher leaked. Bought a used one from Bob\\''s appliances.', 1101963600, '89', 2, 12, 2004);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_property`
-- 

CREATE TABLE `site_property` (
  `prop_id` int(11) NOT NULL auto_increment,
  `prop_address` text NOT NULL,
  `prop_city` varchar(200) NOT NULL default '',
  `prop_state` char(2) NOT NULL default '',
  `prop_zip` varchar(20) NOT NULL default '',
  `prop_description` text NOT NULL,
  `prop_leased_amount` varchar(100) NOT NULL default '',
  `leased_date_start` int(11) NOT NULL default '0',
  `leased_date_end` int(11) NOT NULL default '0',
  `leased_to_name` varchar(100) NOT NULL default '',
  `leased_to_address` text NOT NULL,
  `leased_to_city` varchar(200) NOT NULL default '',
  `leased_to_state` char(2) NOT NULL default '',
  `leased_to_zip` varchar(20) NOT NULL default '',
  `lease_to_phone` varchar(30) NOT NULL default '',
  `leased_to_email` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`prop_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `site_property`
-- 

INSERT INTO `site_property` VALUES (1, '555 West Market Street', 'Nocity', 'CA', '90212', '3 bedroom 2 bath rental unit. 2 stall garage is not for use by renters.', '1450', 1101445200, 1132981200, 'Sally Irent', '555 West Market Street', 'Nocity', 'CA', '90212', '', 'sally.irent@example.com');
INSERT INTO `site_property` VALUES (2, '512 18th Street', 'Whatcity', 'CA', '90222', '2 Bedroom Townhouse. Rents well.', '1300', 1102222800, 1102222800, 'John Doe', '1980 4th Street', 'NoCity', 'CA', '90222', '', 'no@example.com');

-- --------------------------------------------------------

-- 
-- Table structure for table `site_users`
-- 

CREATE TABLE `site_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(100) NOT NULL default '',
  `user_email` varchar(200) NOT NULL default '',
  `user_login` varchar(100) NOT NULL default '',
  `user_password` varchar(100) NOT NULL default '',
  `user_level` int(1) NOT NULL default '0',
  `user_number` varchar(100) NOT NULL default '',
  `student_class` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `site_users`
-- 

INSERT INTO `site_users` VALUES (3, 'Admin', 'admin@example.com', 'admin', 'test', 0, '2134243', 0);
INSERT INTO `site_users` VALUES (8, 'Mary', 'maryj@example.com', 'MaryJ', 'maryj', 0, '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_vars`
-- 

CREATE TABLE `site_vars` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `site_vars`
-- 

