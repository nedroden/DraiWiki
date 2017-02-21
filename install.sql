-- Adminer 4.2.6-dev MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `drai_agreements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_agreements` (`ID`, `body`, `locale`) VALUES
(1,	'You need to accept this.',	'en_US'),
(2,	'Het zou heel fijn zijn als je dit zou willen accepteren.',	'nl_NL');

CREATE TABLE `drai_articles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_ID` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_articles` (`ID`, `title`, `language`, `group_ID`, `status`) VALUES
(1,	'Homepage',	'en_US',	1,	1),
(2,	'About',	'en_US',	2,	1),
(3,	'Welkom!',	'nl_NL',	1,	1);

CREATE TABLE `drai_config` (
  `category` tinytext NOT NULL,
  `identifier` tinytext NOT NULL,
  `value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `drai_config` (`category`, `identifier`, `value`) VALUES
('wiki',	'WIKI_LOCALE',	'en_US'),
('wiki',	'WIKI_NAME',	'DraiWiki'),
('wiki',	'WIKI_SLOGAN',	'Revolutionary wiki software'),
('wiki',	'WIKI_SKIN',	'default'),
('wiki',	'WIKI_IMAGES',	'default'),
('wiki',	'WIKI_TEMPLATES',	'default');

CREATE TABLE `drai_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `article_ID` int(11) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_edited` datetime NOT NULL,
  `edited_by` int(11) NOT NULL,
  `infobox_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_history` (`ID`, `article_ID`, `body`, `date_edited`, `edited_by`, `infobox_ID`) VALUES
(1,	1,	'## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension',	'2017-02-20 19:46:38',	1,	0),
(2,	2,	'We\'re cool.',	'2017-02-21 11:48:45',	1,	0),
(3,	3,	'Dit is alleen om te testen.',	'2017-02-21 12:22:33',	1,	0);

CREATE TABLE `drai_locales` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `native` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dialect` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `homepage` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_locales` (`ID`, `native`, `dialect`, `country`, `code`, `homepage`) VALUES
(1,	'English',	'General American',	'United States',	'en_US',	'homepage'),
(2,	'Nederlands',	'Netherlandic',	'Netherlands',	'nl_NL',	'welkom!');

CREATE TABLE `drai_permission_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `permissions` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_permission_profiles` (`ID`, `permissions`) VALUES
(1,	''),
(2,	''),
(3,	'');

CREATE TABLE `drai_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groups` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activated` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `drai_user_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `permission_profile` int(11) NOT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dominant` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_user_groups` (`ID`, `permission_profile`, `name`, `color`, `dominant`) VALUES
(1,	0,	'Root',	'#000000',	0),
(2,	1,	'Admin',	'#ed6a5a',	0),
(3,	2,	'Regular',	'',	0),
(4,	3,	'Banned',	'#000000',	1);

-- 2017-02-21 18:07:00
