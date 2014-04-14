/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Table structure for table `site_amenities`
--

CREATE TABLE IF NOT EXISTS `site_amenities` (
  `am_id` int(10) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `income` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`am_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_amenity_income_cost`
--

CREATE TABLE IF NOT EXISTS `site_amenity_income_cost` (
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

CREATE TABLE IF NOT EXISTS `site_expenses` (
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

INSERT INTO `site_expenses` (`expense_id`, `expense_prop`, `expense_name`, `expense_description`, `expense_date`, `expense_cost`, `expense_date_day`, `expense_date_month`, `expense_date_year`) VALUES
(1, 1, 'Cleaning', 'Dishwasher leaked, needed to clean carpet.', 1102136400, '39', 4, 12, 2004),
(2, 1, 'Leaf Removal', 'Leaf removal by service', 1099285200, '48', 1, 11, 2004),
(3, 1, 'Dishwasher', 'Dishwasher leaked. Bought a used one from Bob\\''s appliances.', 1101963600, '89', 2, 12, 2004);

-- --------------------------------------------------------

--
-- Table structure for table `site_files`
--

CREATE TABLE IF NOT EXISTS `site_files` (
  `file_num_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_upload` blob,
  `file_type` varchar(20) DEFAULT NULL,
  `file_link` text,
  PRIMARY KEY (`file_num_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_lease_violation`
--

CREATE TABLE IF NOT EXISTS `site_lease_violation` (
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

CREATE TABLE IF NOT EXISTS `site_property` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_address` text NOT NULL,
  `prop_city` varchar(200) NOT NULL DEFAULT '',
  `prop_state` char(2) NOT NULL DEFAULT '',
  `prop_zip` varchar(20) NOT NULL DEFAULT '',
  `prop_description` text NOT NULL,
  `prop_leased_amount` varchar(100) NOT NULL DEFAULT '',
  `leased_date_start` int(11) NOT NULL DEFAULT '0',
  `leased_date_end` int(11) NOT NULL DEFAULT '0',
  `leased_to_name` varchar(100) NOT NULL DEFAULT '',
  `leased_to_address` text NOT NULL,
  `leased_to_city` varchar(200) NOT NULL DEFAULT '',
  `leased_to_state` char(2) NOT NULL DEFAULT '',
  `leased_to_zip` varchar(20) NOT NULL DEFAULT '',
  `lease_to_phone` varchar(30) NOT NULL DEFAULT '',
  `leased_to_email` varchar(200) NOT NULL DEFAULT '' COMMENT 'test',
  `prop_value` decimal(10,2),
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `site_property`
--

INSERT INTO `site_property` (`prop_id`, `prop_address`, `prop_city`, `prop_state`, `prop_zip`, `prop_description`, `prop_leased_amount`, `leased_date_start`, `leased_date_end`, `leased_to_name`, `leased_to_address`, `leased_to_city`, `leased_to_state`, `leased_to_zip`, `lease_to_phone`, `leased_to_email`) VALUES
(1, '555 West Market Street', 'Nocity', 'CA', '90212', '3 bedroom 2 bath rental unit. 2 stall garage is not for use by renters.', '1450', 1101445200, 1132981200, 'Sally Irent', '555 West Market Street', 'Nocity', 'CA', '90212', '', 'sally.irent@example.com'),
(2, '512 18th Street', 'Whatcity', 'CA', '90222', '2 Bedroom Townhouse. Rents well.', '1300', 1102222800, 1102222800, 'John Doe', '1980 4th Street', 'NoCity', 'CA', '90222', '', 'no@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `site_prop_files`
--

CREATE TABLE IF NOT EXISTS `site_prop_files` (
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

CREATE TABLE IF NOT EXISTS `site_prop_unit` (
  `unit_id` int(10) NOT NULL AUTO_INCREMENT,
  `prop_id` int(10) NOT NULL,
  `amenities` text,
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_rent_due`
--

CREATE TABLE IF NOT EXISTS `site_rent_due` (
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

CREATE TABLE IF NOT EXISTS `site_tenants` (
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

CREATE TABLE IF NOT EXISTS `site_tenant_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(10) NOT NULL,
  `file_num_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_tenant_site_history`
--

CREATE TABLE IF NOT EXISTS `site_tenant_site_history` (
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

CREATE TABLE IF NOT EXISTS `site_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL DEFAULT '',
  `user_email` varchar(200) NOT NULL DEFAULT '',
  `user_login` varchar(100) NOT NULL DEFAULT '',
  `user_password` varchar(100) NOT NULL DEFAULT '',
  `user_level` int(1) NOT NULL DEFAULT '0',
  `user_number` varchar(100) NOT NULL DEFAULT '',
  `user_class` int(11) NOT NULL DEFAULT '0',
  `user_super` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_users`
--

INSERT INTO `site_users` (`user_id`, `user_name`, `user_email`, `user_login`, `user_password`, `user_level`, `user_number`, `user_class`, `user_super`) VALUES
(1, 'Admin', 'admin@example.com', 'admin', 'test', 0, '2134243', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `site_vars`
--

CREATE TABLE IF NOT EXISTS `site_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
