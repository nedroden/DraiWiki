<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 *
 * Class information:
 * This class contains the settings.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Config {

	/**
	 * DraiWiki will use the data below to establish a connection to the database.
	 */
	const 
	DB_SERVER = 'localhost',
	DB_USERNAME = 'pasta',
	DB_PASSWORD = '',
	DB_NAME = 'draiwiki',
	DB_PREFIX = 'drai_';
}