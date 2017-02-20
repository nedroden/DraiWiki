-- Adminer 4.2.6-dev MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `drai_articles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_ID` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_articles` (`ID`, `title`, `language`, `group_ID`, `status`) VALUES
(1,	'Homepage',	'en_US',	1,	1);

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
('wiki',	'WIKI_TEMPLATES',	'default'),
('wiki',	'WIKI_HOMEPAGE',	'Homepage');

CREATE TABLE `drai_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `article_ID` int(11) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_edited` datetime NOT NULL,
  `edited_by` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `drai_history` (`ID`, `article_ID`, `body`, `date_edited`, `edited_by`) VALUES
(1,	1,	'Welcome to DraiWiki! This is just a test.',	'2017-02-20 19:46:38',	1);

-- 2017-02-20 19:15:05
