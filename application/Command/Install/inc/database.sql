-- --------------------------------------------------------

-- 
-- Table structure for table `client`
-- 

CREATE TABLE `client` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `personal_code` VARCHAR( 15 ) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `county` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `house` char(5) NOT NULL,
  `apartment` char(5) NOT NULL,
  `address` varchar(1000) NOT NULL COMMENT 'full address',
  `zip` int(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `web` varchar(100) NOT NULL,
  `phone` char(20) NOT NULL,
  `company` varchar(255) NOT NULL,
  `company_register` varchar(20) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `changed_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Clients which are either private persons or companies' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `language`
-- 

CREATE TABLE `language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` longtext,
  `value` longtext,
  `language` char(10) DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language` (`language`),
  KEY `model` (`model`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

-- 
-- Table structure for table `log`
-- 

CREATE TABLE `log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(150) NOT NULL,
  `table_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `data` longtext NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `table_name` (`table_name`),
  KEY `table_id` (`table_id`),
  KEY `action` (`action`),
  KEY `created_by_id` (`created_by_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

-- 
-- Table structure for table `privilege`
-- 

CREATE TABLE `privilege` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `route` varchar(25) NOT NULL,
  `class` varchar(25) NOT NULL,
  `method` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `privilege`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `role`
-- 

CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `is_enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `level` smallint(6) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Roles for system users' AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `role`
-- 

INSERT INTO `role` (`id`, `name`, `description`, `is_enabled`, `created_at`, `level`, `type`) VALUES (0, 'ALL', 'ALL', 1, NOW(), 15, '*'),
(1, 'SUPERADMIN', 'super administrator', 1, NOW(), 10, 'SUPERADMIN'),
(2, 'USER', 'not logged user 2', 1, NOW(), 1, 'USER'),
(3, 'CLIENT', 'logged user', 1, NOW(), 2, 'CLIENT'),
(4, 'ADMIN', 'admin', 1, NOW(), 9, 'ADMIN'),
(5, 'SYSTEM', 'system (cron) user', 1, NOW(), 10, 'SYSTEM');

UPDATE `role` SET id = 0 WHERE type = '*';

-- --------------------------------------------------------

-- 
-- Table structure for table `route`
-- 

CREATE TABLE `route` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `canonical_url` varchar(1000) NOT NULL,
  `logical_url` varchar(1000) NOT NULL,
  `is_indexed` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `canonical_url` (`canonical_url`(255)),
  KEY `parent_id` (`parent_id`),
  KEY `logical_url` (`logical_url`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `route`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `template`
-- 

CREATE TABLE `template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `language` char(5) NOT NULL,
  `content` longtext,
  `subject` varchar(255) NOT NULL,
  `from_name` varchar(255) NOT NULL,
  `from_email` varchar(100) NOT NULL,
  `file` varchar(1000) NOT NULL COMMENT 'JSON',
  `is_active` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `template`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tmp_password` varchar(255) NOT NULL,
  `type` varchar(15) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT '1',
  `password_expires_at` date DEFAULT NULL,
  `account_expires_at` date DEFAULT NULL,
  `activation_hash` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='System users having access to the information system' AUTO_INCREMENT=1 ;


CREATE TRIGGER TR_client
BEFORE INSERT ON client
FOR EACH ROW
BEGIN
DECLARE fullAddress VARCHAR(1000) DEFAULT '';

IF LENGTH(new.country) > 0 THEN
	SET new.country = CONCAT(UCASE(LEFT(new.country, 1)), LCASE(SUBSTRING(new.country, 2)));
	SET fullAddress = new.country;
END IF;

IF LENGTH(new.county) > 0 THEN
	SET new.county = CONCAT(UCASE(LEFT(new.county, 1)), LCASE(SUBSTRING(new.county, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.county);
END IF;

IF LENGTH(new.city) > 0 THEN
	SET new.city = CONCAT(UCASE(LEFT(new.city, 1)), LCASE(SUBSTRING(new.city, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.city);
END IF;

IF LENGTH(new.street) > 0 THEN
	SET new.street = CONCAT(UCASE(LEFT(new.street, 1)), LCASE(SUBSTRING(new.street, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.street);
END IF;

IF LENGTH(new.house) > 0 THEN
	SET fullAddress = CONCAT(fullAddress, ' ', new.house);
END IF;

IF LENGTH(new.apartment) > 0 THEN
	SET fullAddress = CONCAT(fullAddress, ' - ', new.apartment);
END IF;

SET new.address = fullAddress;

END;


CREATE TRIGGER TR_client_update
BEFORE UPDATE ON client
FOR EACH ROW
BEGIN
DECLARE fullAddress VARCHAR(1000) DEFAULT '';

IF LENGTH(new.country) > 0 THEN
	SET new.country = CONCAT(UCASE(LEFT(new.country, 1)), LCASE(SUBSTRING(new.country, 2)));
	SET fullAddress = new.country;
END IF;

IF LENGTH(new.county) > 0 THEN
	SET new.county = CONCAT(UCASE(LEFT(new.county, 1)), LCASE(SUBSTRING(new.county, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.county);
END IF;

IF LENGTH(new.city) > 0 THEN
	SET new.city = CONCAT(UCASE(LEFT(new.city, 1)), LCASE(SUBSTRING(new.city, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.city);
END IF;

IF LENGTH(new.street) > 0 THEN
	SET new.street = CONCAT(UCASE(LEFT(new.street, 1)), LCASE(SUBSTRING(new.street, 2)));
	SET fullAddress = CONCAT(fullAddress, ', ', new.street);
END IF;

IF LENGTH(new.house) > 0 THEN
	SET fullAddress = CONCAT(fullAddress, ' ', new.house);
END IF;

IF LENGTH(new.apartment) > 0 THEN
	SET fullAddress = CONCAT(fullAddress, ' - ', new.apartment);
END IF;

SET new.address = fullAddress;

END;