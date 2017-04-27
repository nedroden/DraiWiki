-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2017 at 07:26 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `draiwiki`
--
CREATE DATABASE IF NOT EXISTS `draiwiki` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `draiwiki`;

-- --------------------------------------------------------

--
-- Table structure for table `drai_agreements`
--

CREATE TABLE `drai_agreements` (
  `ID` int(11) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_agreements`
--

INSERT INTO `drai_agreements` (`ID`, `body`, `locale`) VALUES
(1, 'You need to accept this.', 'en_US'),
(2, 'Het zou heel fijn zijn als je dit zou willen accepteren.', 'nl_NL');

-- --------------------------------------------------------

--
-- Table structure for table `drai_articles`
--

CREATE TABLE `drai_articles` (
  `ID` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_ID` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_articles`
--

INSERT INTO `drai_articles` (`ID`, `title`, `language`, `group_ID`, `status`) VALUES
(1, 'Homepage', 'en_US', 1, 1),
(2, 'About', 'en_US', 2, 1),
(3, 'Welkom!', 'nl_NL', 1, 1),
(4, 'Sua_negant', 'en_US', 3, 1),
(5, 'Adspice_dum_pater_flerunt', 'en_US', 4, 1),
(6, 'Sucis_deos_iterum_umbras_niveae', 'en_US', 5, 1),
(7, 'Federal_Republic_of_Eron', 'en_US', 6, 1),
(8, 'Добро_пожаловать!', 'ru_RU', 1, 1),
(9, 'This is a test', 'en_US', 0, 1),
(10, 'My new article', 'en_US', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `drai_config`
--

CREATE TABLE `drai_config` (
  `category` tinytext NOT NULL,
  `identifier` tinytext NOT NULL,
  `value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drai_config`
--

INSERT INTO `drai_config` (`category`, `identifier`, `value`) VALUES
('wiki', 'WIKI_LOCALE', 'en_US'),
('wiki', 'WIKI_NAME', 'DraiWiki'),
('wiki', 'WIKI_SLOGAN', 'Revolutionary wiki software'),
('wiki', 'WIKI_SKIN', 'default'),
('wiki', 'WIKI_IMAGES', 'default'),
('wiki', 'WIKI_TEMPLATES', 'default'),
('user', 'MIN_FIRST_NAME_LENGTH', '3'),
('user', 'MIN_LAST_NAME_LENGTH', '3'),
('user', 'MAX_FIRST_NAME_LENGTH', '15'),
('user', 'MAX_LAST_NAME_LENGTH', '20'),
('user', 'MIN_PASSWORD_LENGTH', '5'),
('user', 'MAX_PASSWORD_LENGTH', '35'),
('user', 'MIN_EMAIL_LENGTH', '5'),
('user', 'MAX_EMAIL_LENGTH', '25'),
('user', 'SALT', '98h#_al04sNGd#$4u98732nasG__'),
('session', 'COOKIE_ID', 'DraiWikiDev10'),
('user', 'ENABLE_REGISTRATION', '1'),
('article', 'MIN_BODY_LENGTH', '30'),
('article', 'MIN_TITLE_LENGTH', '3'),
('article', 'MAX_TITLE_LENGTH', '30');

-- --------------------------------------------------------

--
-- Table structure for table `drai_history`
--

CREATE TABLE `drai_history` (
  `ID` int(11) NOT NULL,
  `article_ID` int(11) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_edited` datetime NOT NULL,
  `edited_by` int(11) NOT NULL,
  `infobox_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_history`
--

INSERT INTO `drai_history` (`ID`, `article_ID`, `body`, `date_edited`, `edited_by`, `infobox_ID`) VALUES
(1, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-02-20 19:46:38', 1, 0),
(2, 2, 'We\'re cool.</textarea><strong>', '2017-02-21 11:48:45', 1, 0),
(3, 3, 'Dit is alleen om te testen.', '2017-02-21 12:22:33', 1, 0),
(4, 4, '## Nutrix gemitum fluctus habentem tum saxum imago\r\n\r\nLorem markdownum lepus dant est cruentae, *indueret versa*, est locum velut est,\r\nsolet nomen dixit! Aer fulvis axem parente crinis: labori eras creator: diurnis\r\na tumulo tamen, Nycteus sternentemque utque.\r\n```javascript\r\n    outputStandby += 3 - bar;\r\n    if (-2 != logic_wheel) {\r\n        eide_map.macintosh(extranet(3, rootkit, php_ibm), 3);\r\n        text_digital_exabyte(43);\r\n        direct_wavelength_bezel += 2 / interlaced;\r\n    }\r\n    multiprocessingInteger += fifo;\r\n```\r\n[Amori levat per](http://inovaporem.org/) fuerat **praedae Athamanta**, tabe\r\nmagis armis omnia metu Troius illa. Egerat faunique uterque eandem [patefecit me\r\nquerellis](http://thyrsofraterno.com/), non gnato illas ex pondus. Altoque\r\nspatiosa repetebam gramine verbis contraria fecerat: **inde tenuissima virgo**\r\nducere alumnae liquido. Porrigit mollito frondibus venit; heros sanguine; nec\r\ntotiens regia? Tum manu solvit latos unum est; dixisse, **quam et**, si\r\n**conscia** densi inritaturque tempora certas rege nunc?\r\n\r\n## Hac Phrygiae poena\r\n\r\nHostis tangit servat capit proterva tethys alimenta est Aurora planissima,\r\nclaudit neu. Hoc *collo perdis caelobracchia* sub fassus herbosaque locus\r\nsustulit erat; illa esse, aemulus. Arma teli dixit caput hanc procorum novus.\r\nHaec motos spectat infringere cava; dat cupressu cutis, [alba\r\nradiis](http://www.enses.org/vitta). Certe se maris verba per.\r\n\r\nEst futura in deam, fecitque *poscebat fugam* instant. Mole pluma *conata*. Hunc\r\nannus sidera et telis vetaris, quid acer rapidi Aeacidae. Novitate [vident\r\nDelphi](http://nec.io/baca).\r\n\r\n1. Optastis omnipotens adgnovitque nomen\r\n2. Nec iam laboriferi paternas insistere Aeginae festum\r\n3. Miserabilis inpressa Aurorae\r\n4. Verba imago tibi sed\r\n\r\nSonabat ferunt, sacrorum? **Vidi loco** fratresque movet **fissa**, nam dextra\r\npedum iunxit. Notam tunc fratres, Tempe margine os ambo inter rogarem, et hic.\r\n\r\nAdspexisse murmura verba, et incipit **ignara** festum, haustus dicta\r\npraesensque! Admoto niveis in erat Cythereia tenuit; planamque cubat. Et Nostra\r\ngaleae Iovi [ultorem](http://retinete.org/captam-ille), quoque labor quam ore\r\nsublimis nota; non fugae. Thyrsos sustulit praeceps nullaque praedam sic\r\nsuperest ligati aegra levarit **pendet**, laudisque. Quae sanguine mente, non\r\ntuba unda?', '2017-02-26 15:30:42', 1, 0),
(5, 5, '## Querellae tamen\r\n\r\nLorem markdownum timor si exue oscula, plus iuravimus altaria quae *relinquit*\r\nregnat tenues. Imas opibus [revelli\r\ndevotaque](http://www.harenalatus.org/litore.php) caelestia occupat quod.\r\nInducta constitit vicem; cremabis est agricolis dederant *neque* matribus est\r\nnunc, Notum.\r\n\r\n    var activexThunderbolt = 108119;\r\n    encoding_boot_piconet(40, 465225, tcp_rdf_publishing);\r\n    menuPpgaIt(771789, inkjetPowerDirect);\r\n    installer_online.storage_gigahertz = defragment(cache) + skinPostSrgb -\r\n            kdeStation;\r\n    macintosh_array -= scalablePack;\r\n\r\nEt Bacchum sustinet proiecto porrexit valuisse fameque. **Visum** crimen eiectas\r\nperiere tractus in curvo et ulla nomine, si esse profundum te nati.\r\n\r\n## Primus et\r\n\r\nMeum membra subnectite formam sumit. Potest mihi: in ruborem silvis me mihi\r\nrefert ipsae curvum in vivit acies? Mollitaque hic mellis Lapithaeae parentem,\r\nlicet poenam **illum** valuere cultoribus sic rorantia. Auxilium pignora si iam\r\nduro ossa discordia ponti, in qua hunc, uvis, opes aquis. Patremque aurum, est\r\n**totis senectus** inter cognataque mutataeque inque in scelus iuvenes.\r\n\r\n> Sceleris nihil, moly iuvenes, per gloria texerat, est? Probavit superba\r\n> attonuit nocens in in letum, fore quae miranti paulum, in\r\n> [auctor](http://urbequodcumque.io/), unam sua.\r\n\r\nMotura elisarum tu terrae vos inquit Narve, ego, qui non summa haustus requirit.\r\nIuvencae novem pallent expulit distinxit attenuatus sic dabat nequeam iaces\r\nCycladas quamvis lucifer, tamen. Matre hanc viscera artus: vetitum traiecit\r\ndimotis cupidine herbas cum legem ad tamen. Non incurva *serpunt*, ingreditur\r\neodem nubila, videt classes est Liber alis a undas oculos pulvis, bella litora.\r\nGelido meminit attoniti [purgamina](http://pecudesque.com/concedeamenanus)\r\ndemittite illis iaculum, epulis dolor; tunc cremet Mavors pensandum ulnis\r\nOdrysius.\r\n\r\n    digitalFiosRw(irq_jsp_oop, 50, 3 + linkedinSouthbridge);\r\n    directBmp = serverJquery.nic_multithreading_ip(-5, nullPrinterLag) +\r\n            clean_flops_biometrics.ieee_samba(optical, seoDdl - laptop_card_ddl,\r\n            proxy + 2);\r\n    memory_kilobyte_power += esports.station_software.shareware(-4 / 5, 1) /\r\n            wiKerningAiff;\r\n\r\nAit truncoque rumpo, esse foramine iamque advertitur per signum. Intumuit et\r\nvillo, flumina, Boreae annosa, Andraemone, viribus iussa, deam huc, tegit\r\n*mare*. Decertare a viscera detinet, artificis attrahit fronde. Qui est, nec\r\n[male euntis](http://www.tamenin.org/) inductas serta matre?', '2017-02-26 15:31:40', 1, 0),
(6, 6, '## Lingua ego atque\r\n\r\nLorem markdownum, **victam remis**, tumulo senioribus terrae tendentem mansit\r\n*si* vitta sidus. Gentes mihi Cecropidas foedantem multaque putavi virginitas\r\nlitoris utriusque quantusque **hinc**. Quam nec, atria hiemalibus folioque\r\npallet, dicere pro proxima muneris cum vocat illic dum ut. Cetera hanc: est\r\niubet; carinam Iubam **accepit** fonti inque hanc senectus, sed?\r\n\r\nPostquam nec, est adnuerat, non illis aut! Minacia urbis fieri *sua*; utrumque\r\nsed unum a tempora.\r\n\r\n## Litora portae in videri naturale cruoris iuvenem\r\n\r\nUvis madefacta [hoc](http://et.com/colitin.html) inploraret intortos. Haud ebur\r\nomnia ut duplex esse exstructas esse animavit, quae lacrimis ducta\r\n[sic](http://www.occallescere-urbes.net/nequeotamen) Hecaten. Quod imagine,\r\nsupplicat, sic pudorque effigiem artem cortice stetit **mare classes**.\r\n\r\n1. Feruntur plectrumque actus\r\n2. Quod cum volucris mihi\r\n3. Valebant occubuit putat pars spem alga imperiumque\r\n4. Loqui colubrasque sequendi aditumque amores manibusque hamato\r\n5. Leones avido per veniam relinquit vates\r\n6. Et uberior tegitur\r\n\r\n## Obstabat quam a furores\r\n\r\nQuarto totidem primumque loquendo aut hortos disci dum vepre dea nullum sedebat.\r\nAn nescia cura haut abdita, et reserabo, scilicet visa aderant.\r\n\r\n- Cum donec expalluit summos rapinae ille\r\n- Victa Orithyian parentes\r\n- Non nec umerique ab semper tantum somnum\r\n- Praesens contingere\r\n\r\nSpelunca facibus pondere fulvaque respicere quae scintilla! Haemum suis vereri\r\nteli, sensurus, est nec forma! Qua et pigra aevi forma micantes gradere\r\ndiscidium indignantesque inducta me numero **Lapithae** fer facto sparsas: dixi.\r\nPostquam habes. Nondum ignem maestus, [tot altior](http://www.aequora.org/):\r\npietatis comantur precari!\r\n\r\nObliquo oscula, sua Bacchus ultra. Barba in Oceanumque fibras, Dryopen villosis\r\ntitulum plaustri ei Cecrope dicit; currus et quorum.', '2017-02-26 15:35:08', 1, 0),
(7, 7, '## History\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion', '2017-03-07 09:20:10', 1, 0),
(8, 8, 'Всем привет! Как дела?))', '2017-03-07 18:12:58', 1, 0),
(9, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-18 13:45:41', 5, 0),
(10, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-18 13:45:57', 5, 0),
(11, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion', '2017-03-18 13:46:29', 5, 0),
(12, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-24 13:44:28', 5, 0),
(13, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!\r\n\r\ntest', '2017-03-24 13:44:41', 5, 0),
(14, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-24 13:45:00', 5, 0),
(15, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-24 13:46:30', 5, 0),
(16, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!e', '2017-03-26 15:15:42', 5, 0),
(17, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use. Just testing.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however. The admin panel allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n* PHP 5.6+\r\n* MySQL\r\n* PDO extension\r\n* Composer\r\n\r\n### 2.2. How to install\r\n1. Install Composer\r\n2. Download the most recent code from Github\r\n3. Extract the files to your http directory\r\n4. Use Composer to install the required packages (run _composer install_ from the terminal/command prompt)\r\n5. Edit the configuration file in public/config\r\n6. Import the database tables (install.sql)\r\n7. Enjoy!', '2017-03-26 15:15:49', 5, 0),
(18, 2, 'We\'re cool.</textarea><strong>test', '2017-03-26 19:09:20', 5, 0),
(19, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-03-27 18:21:09', 5, 0),
(20, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-04-07 10:15:19', 5, 0),
(21, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-04-07 10:15:27', 5, 0),
(22, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-04-07 10:15:41', 5, 0),
(23, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-04-07 10:17:55', 5, 0),
(24, 7, '## History\r\nEron is an amazing country.\r\n\r\n### War of independence\r\n### World War 2\r\n\r\n## Demographics\r\n### Languages\r\n### Religion\r\nTest', '2017-04-07 10:18:08', 5, 0),
(25, 4, '## Nutrix gemitum fluctus habentem tum saxum imago\r\n\r\nLorem markdownum lepus dant est cruentae, *indueret versa*, est locum velut est,\r\nsolet nomen dixit! Aer fulvis axem parente crinis: labori eras creator: diurnis\r\na tumulo tamen, Nycteus sternentemque utque.\r\n```javascript\r\n    outputStandby += 3 - bar;\r\n    if (-2 != logic_wheel) {\r\n        eide_map.macintosh(extranet(3, rootkit, php_ibm), 3);\r\n        text_digital_exabyte(43);\r\n        direct_wavelength_bezel += 2 / interlaced;\r\n    }\r\n    multiprocessingInteger += fifo;\r\n```\r\n[Amori levat per](http://inovaporem.org/) fuerat **praedae Athamanta**, tabe\r\nmagis armis omnia metu Troius illa. Egerat faunique uterque eandem [patefecit me\r\nquerellis](http://thyrsofraterno.com/), non gnato illas ex pondus. Altoque\r\nspatiosa repetebam gramine verbis contraria fecerat: **inde tenuissima virgo**\r\nducere alumnae liquido. Porrigit mollito frondibus venit; heros sanguine; nec\r\ntotiens regia? Tum manu solvit latos unum est; dixisse, **quam et**, si\r\n**conscia** densi inritaturque tempora certas rege nunc?\r\n\r\n## Hac Phrygiae poena\r\n\r\nHostis tangit servat capit proterva tethys alimenta est Aurora planissima,\r\nclaudit neu. Hoc *collo perdis caelobracchia* sub fassus herbosaque locus\r\nsustulit erat; illa esse, aemulus. Arma teli dixit caput hanc procorum novus.\r\nHaec motos spectat infringere cava; dat cupressu cutis, [alba\r\nradiis](http://www.enses.org/vitta). Certe se maris verba per.\r\n\r\nEst futura in deam, fecitque *poscebat fugam* instant. Mole pluma *conata*. Hunc\r\nannus sidera et telis vetaris, quid acer rapidi Aeacidae. Novitate [vident\r\nDelphi](http://nec.io/baca).\r\n\r\n1. Optastis omnipotens adgnovitque nomen\r\n2. Nec iam laboriferi paternas insistere Aeginae festum\r\n3. Miserabilis inpressa Aurorae\r\n4. Verba imago tibi sed\r\n\r\nSonabat ferunt, sacrorum? **Vidi loco** fratresque movet **fissa**, nam dextra\r\npedum iunxit. Notam tunc fratres, Tempe margine os ambo inter rogarem, et hic.\r\n\r\nAdspexisse murmura verba, et incipit **ignara** festum, haustus dicta\r\npraesensque! Admoto niveis in erat Cythereia tenuit; planamque cubat. Et Nostra\r\ngaleae Iovi [ultorem](http://retinete.org/captam-ille), quoque labor quam ore\r\nsublimis nota; non fugae. Thyrsos sustulit praeceps nullaque praedam sic\r\nsuperest ligati aegra levarit **pendet**, laudisque. Quae sanguine mente, non\r\ntuba unda?', '2017-04-07 10:18:40', 5, 0),
(26, 4, '## Nutrix gemitum fluctus habentem tum saxum imago\r\n\r\nLorem markdownum lepus dant est cruentae, *indueret versa*, est locum velut est,\r\nsolet nomen dixit! Aer fulvis axem parente crinis: labori eras creator: diurnis\r\na tumulo tamen, Nycteus sternentemque utque.\r\n```javascript\r\n    outputStandby += 3 - bar;\r\n    if (-2 != logic_wheel) {\r\n        eide_map.macintosh(extranet(3, rootkit, php_ibm), 3);\r\n        text_digital_exabyte(43);\r\n        direct_wavelength_bezel += 2 / interlaced;\r\n    }\r\n    multiprocessingInteger += fifo;\r\n```\r\n[Amori levat per](http://inovaporem.org/) fuerat **praedae Athamanta**, tabe\r\nmagis armis omnia metu Troius illa. Egerat faunique uterque eandem [patefecit me\r\nquerellis](http://thyrsofraterno.com/), non gnato illas ex pondus. Altoque\r\nspatiosa repetebam gramine verbis contraria fecerat: **inde tenuissima virgo**\r\nducere alumnae liquido. Porrigit mollito frondibus venit; heros sanguine; nec\r\ntotiens regia? Tum manu solvit latos unum est; dixisse, **quam et**, si\r\n**conscia** densi inritaturque tempora certas rege nunc?\r\n\r\n## Hac Phrygiae poena\r\n\r\nHostis tangit servat capit proterva tethys alimenta est Aurora planissima,\r\nclaudit neu. Hoc *collo perdis caelobracchia* sub fassus herbosaque locus\r\nsustulit erat; illa esse, aemulus. Arma teli dixit caput hanc procorum novus.\r\nHaec motos spectat infringere cava; dat cupressu cutis, [alba\r\nradiis](http://www.enses.org/vitta). Certe se maris verba per.\r\n\r\nEst futura in deam, fecitque *poscebat fugam* instant. Mole pluma *conata*. Hunc\r\nannus sidera et telis vetaris, quid acer rapidi Aeacidae. Novitate [vident\r\nDelphi](http://nec.io/baca).\r\n\r\n1. Optastis omnipotens adgnovitque nomen\r\n2. Nec iam laboriferi paternas insistere Aeginae festum\r\n3. Miserabilis inpressa Aurorae\r\n4. Verba imago tibi sed\r\n\r\nSonabat ferunt, sacrorum? **Vidi loco** fratresque movet **fissa**, nam dextra\r\npedum iunxit. Notam tunc fratres, Tempe margine os ambo inter rogarem, et hic.\r\n\r\nAdspexisse murmura verba, et incipit **ignara** festum, haustus dicta\r\npraesensque! Admoto niveis in erat Cythereia tenuit; planamque cubat. Et Nostra\r\ngaleae Iovi [ultorem](http://retinete.org/captam-ille), quoque labor quam ore\r\nsublimis nota; non fugae. Thyrsos sustulit praeceps nullaque praedam sic\r\nsuperest ligati aegra levarit **pendet**, laudisque. Quae sanguine mente, non\r\ntuba unda?', '2017-04-07 10:20:49', 5, 0),
(27, 5, '## Querellae tamen\r\n\r\nLorem markdownum timor si exue oscula, plus iuravimus altaria quae *relinquit*\r\nregnat tenues. Imas opibus [revelli\r\ndevotaque](http://www.harenalatus.org/litore.php) caelestia occupat quod.\r\nInducta constitit vicem; cremabis est agricolis dederant *neque* matribus est\r\nnunc, Notum.\r\n\r\n    var activexThunderbolt = 108119;\r\n    encoding_boot_piconet(40, 465225, tcp_rdf_publishing);\r\n    menuPpgaIt(771789, inkjetPowerDirect);\r\n    installer_online.storage_gigahertz = defragment(cache) + skinPostSrgb -\r\n            kdeStation;\r\n    macintosh_array -= scalablePack;\r\n\r\nEt Bacchum sustinet proiecto porrexit valuisse fameque. **Visum** crimen eiectas\r\nperiere tractus in curvo et ulla nomine, si esse profundum te nati.\r\n\r\n## Primus et\r\n\r\nMeum membra subnectite formam sumit. Potest mihi: in ruborem silvis me mihi\r\nrefert ipsae curvum in vivit acies? Mollitaque hic mellis Lapithaeae parentem,\r\nlicet poenam **illum** valuere cultoribus sic rorantia. Auxilium pignora si iam\r\nduro ossa discordia ponti, in qua hunc, uvis, opes aquis. Patremque aurum, est\r\n**totis senectus** inter cognataque mutataeque inque in scelus iuvenes.\r\n\r\n> Sceleris nihil, moly iuvenes, per gloria texerat, est? Probavit superba\r\n> attonuit nocens in in letum, fore quae miranti paulum, in\r\n> [auctor](http://urbequodcumque.io/), unam sua.\r\n\r\nMotura elisarum tu terrae vos inquit Narve, ego, qui non summa haustus requirit.\r\nIuvencae novem pallent expulit distinxit attenuatus sic dabat nequeam iaces\r\nCycladas quamvis lucifer, tamen. Matre hanc viscera artus: vetitum traiecit\r\ndimotis cupidine herbas cum legem ad tamen. Non incurva *serpunt*, ingreditur\r\neodem nubila, videt classes est Liber alis a undas oculos pulvis, bella litora.\r\nGelido meminit attoniti [purgamina](http://pecudesque.com/concedeamenanus)\r\ndemittite illis iaculum, epulis dolor; tunc cremet Mavors pensandum ulnis\r\nOdrysius.\r\n\r\n    digitalFiosRw(irq_jsp_oop, 50, 3 + linkedinSouthbridge);\r\n    directBmp = serverJquery.nic_multithreading_ip(-5, nullPrinterLag) +\r\n            clean_flops_biometrics.ieee_samba(optical, seoDdl - laptop_card_ddl,\r\n            proxy + 2);\r\n    memory_kilobyte_power += esports.station_software.shareware(-4 / 5, 1) /\r\n            wiKerningAiff;\r\n\r\nAit truncoque rumpo, esse foramine iamque advertitur per signum. Intumuit et\r\nvillo, flumina, Boreae annosa, Andraemone, viribus iussa, deam huc, tegit\r\n*mare*. Decertare a viscera detinet, artificis attrahit fronde. Qui est, nec\r\n[male euntis](http://www.tamenin.org/) inductas serta matre?', '2017-04-07 10:21:08', 5, 0),
(28, 1, '# DraiWiki\r\n## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however, because the admin panel also allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 5.6+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n#### 2.1.2. Recommended\r\n* PHP 7.0+\r\n* MariaDB\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone http://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Import the database tables (install.sql)\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n### 2.3.2. Help! My hosting doesn\'t support the minimum required PHP version!\r\nAt the time of writing, the minimum required PHP version is 5.6. If your host is running PHP 5.5, you should consider asking them to upgrade their PHP version. If your host is using an even older version, you should _insist_ that they upgrade their PHP as soon as possible.\r\n', '2017-04-10 16:48:35', 1, 0),
(29, 1, '# DraiWiki\r\n## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however, because the admin panel also allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 5.6+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n#### 2.1.2. Recommended\r\n* PHP 7.0+\r\n* MariaDB\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone http://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Import the database tables (install.sql)\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n### 2.3.2. Help! My hosting doesn\'t support the minimum required PHP version!\r\nAt the time of writing, the minimum required PHP version is 5.6. If your host is running PHP 5.5, you should consider asking them to upgrade their PHP version. If your host is using an even older version, you should _insist_ that they upgrade their PHP as soon as possible.\r\n', '2017-04-10 16:50:31', 1, 0),
(30, 1, '# DraiWiki\r\n## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however, because the admin panel also allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 5.6+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n#### 2.1.2. Recommended\r\n* PHP 7.0+\r\n* MariaDB\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone http://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Import the database tables (install.sql)\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n#### 2.3.2. Help! My hosting doesn\'t support the minimum required PHP version!\r\nAt the time of writing, the minimum required PHP version is 5.6. If your host is running PHP 5.5, you should consider asking them to upgrade their PHP version. If your host is using an even older version, you should _insist_ that they upgrade their PHP as soon as possible.\r\n', '2017-04-10 16:50:50', 1, 0),
(31, 1, '## 1. Introduction to DraiWiki\r\n### 1.1. What is DraiWiki?\r\nDraiWiki is an upcoming open source wiki software that is designed to be customizable, neat-looking, secure and easy to use.\r\n\r\n### 1.2. Why use DraiWiki?\r\nThere are other free wiki softwares out there, so you might be wondering, what makes DraiWiki the best choice for your website? Well, there are several reasons.\r\n\r\nFirst of all, the software is designed to be customizable. For example, a theme consists of three parts: images, CSS and templates. Basically, what you\'ll be able to do is this: you can use the image set from the default theme, while using the CSS of a 3rd party theme, while using the templates of yet another 3rd party theme. And the best thing is: it\'ll only take a few seconds to set up.\r\n\r\nIt also has built-in multi-language support, meaning you won\'t need an extension.\r\n\r\nThe admin panel is designed to be self-sufficient and isolated (i.e. it has its own files), meaning that if you break something, 90% of the time you\'ll be able to fix it from within the admin panel. That\'s not all, however, because the admin panel also allows you to make changes without much effort.\r\n\r\n## 2. Installation\r\n### 2.1. Server requirements\r\n#### 2.1.1. Minimum\r\n* PHP 5.6+\r\n* MariaDB / MySQL\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n#### 2.1.2. Recommended\r\n* PHP 7.0+\r\n* MariaDB\r\n* PDO extension\r\n* Composer\r\n* NPM\r\n\r\n### 2.2. How to install\r\n1. Install Composer and NPM. If you\'re on a shared hosting and can\'t use the terminal, look at section 2.3\r\n2. cd to your http directory\r\n3. Run the following command in your command prompt or terminal: git clone http://github.com/Chistaen/DraiWiki.git\r\n4. Use Composer to install the required packages (composer install)\r\n5. Use NPM to install the required JS libraries (npm install)\r\n6. Edit the configuration file in public/config. Make sure you also edit the BASE_DIRNAME setting\r\n7. Import the database tables (install.sql)\r\n8. Enjoy!\r\n\r\n### 2.3. Troubleshooting\r\n#### 2.3.1. Help! I don\'t have access to a terminal!\r\nIf you\'re on a shared hosting that doesn\'t allow you to install Composer/NPM, don\'t worry. There\'s another solution. Just download the files to your computer and install the Composer and NPM packages from your computer\'s terminal. Then re-upload the files to your hosting. Happy writing!\r\n\r\n#### 2.3.2. Help! My hosting doesn\'t support the minimum required PHP version!\r\nAt the time of writing, the minimum required PHP version is 5.6. If your host is running PHP 5.5, you should consider asking them to upgrade their PHP version. If your host is using an even older version, you should _insist_ that they upgrade their PHP as soon as possible.\r\n', '2017-04-23 13:31:52', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `drai_locales`
--

CREATE TABLE `drai_locales` (
  `ID` int(11) NOT NULL,
  `native` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dialect` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `homepage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_locales`
--

INSERT INTO `drai_locales` (`ID`, `native`, `dialect`, `country`, `code`, `homepage`) VALUES
(1, 'English', 'General American', 'United States', 'en_US', 1),
(2, 'Nederlands', 'Netherlandic', 'Netherlands', 'nl_NL', 3),
(3, 'Русский', 'Russian', 'Russia', 'ru_RU', 8);

-- --------------------------------------------------------

--
-- Table structure for table `drai_log_updates`
--

CREATE TABLE `drai_log_updates` (
  `ID` int(11) NOT NULL,
  `to_version` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `performed_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drai_permission_profiles`
--

CREATE TABLE `drai_permission_profiles` (
  `ID` int(11) NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_permission_profiles`
--

INSERT INTO `drai_permission_profiles` (`ID`, `label`, `permissions`) VALUES
(1, 'admin', 'edit_articles;access_admin;view_history'),
(2, 'regular', 'edit_articles;view_history'),
(3, 'banned', ''),
(4, 'guest', '');

-- --------------------------------------------------------

--
-- Table structure for table `drai_sessions`
--

CREATE TABLE `drai_sessions` (
  `session_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drai_users`
--

CREATE TABLE `drai_users` (
  `ID` int(11) NOT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `registration_date` date NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groups` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferences` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `edits` int(11) NOT NULL,
  `ip_address` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `activated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drai_user_groups`
--

CREATE TABLE `drai_user_groups` (
  `ID` int(11) NOT NULL,
  `permission_profile` int(11) NOT NULL,
  `name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `dominant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drai_user_groups`
--

INSERT INTO `drai_user_groups` (`ID`, `permission_profile`, `name`, `color`, `dominant`) VALUES
(1, 0, 'Root', '#000', 0),
(2, 1, 'Admin', '#ed6a5a', 0),
(3, 2, 'Regular', '', 0),
(4, 3, 'Banned', '#000', 1),
(5, 4, 'Guest', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drai_agreements`
--
ALTER TABLE `drai_agreements`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_articles`
--
ALTER TABLE `drai_articles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_history`
--
ALTER TABLE `drai_history`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_locales`
--
ALTER TABLE `drai_locales`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_log_updates`
--
ALTER TABLE `drai_log_updates`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_permission_profiles`
--
ALTER TABLE `drai_permission_profiles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_sessions`
--
ALTER TABLE `drai_sessions`
  ADD PRIMARY KEY (`session_key`);

--
-- Indexes for table `drai_users`
--
ALTER TABLE `drai_users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `drai_user_groups`
--
ALTER TABLE `drai_user_groups`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drai_agreements`
--
ALTER TABLE `drai_agreements`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `drai_articles`
--
ALTER TABLE `drai_articles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `drai_history`
--
ALTER TABLE `drai_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `drai_locales`
--
ALTER TABLE `drai_locales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `drai_log_updates`
--
ALTER TABLE `drai_log_updates`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drai_permission_profiles`
--
ALTER TABLE `drai_permission_profiles`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `drai_users`
--
ALTER TABLE `drai_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `drai_user_groups`
--
ALTER TABLE `drai_user_groups`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
