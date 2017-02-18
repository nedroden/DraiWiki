SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `drai_articles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `body` longtext NOT NULL,
  `is_visible` int(1) NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('wiki',	'WIKI_HOMEPAGE',	'Home');