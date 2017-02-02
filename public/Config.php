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

/**
 * @todo	Turn class into a singleton
 */
class Config {

	private $_settings;

	public function __construct() {
		/**
		 * DraiWiki will use the data below to establish a connection to the database.
		 */
		$this->_settings['database'] = [ 
			'DB_SERVER' => '127.0.0.1',
			'DB_USERNAME' => 'pasta',
			'DB_PASSWORD' => '',
			'DB_NAME' => 'draiwiki',
			'DB_PREFIX' => 'drai_',
			'DB_CHARSET' => 'utf8mb4'
		];

		/**
		 * Wiki settings. Note: will probably be moved to the database in the future.
		 */
		$this->_settings['wiki'] = [
			'WIKI_NAME' => 'DraiWiki',
			'WIKI_SLOGAN' => 'Revolutionary wiki software',
			'WIKI_SKIN' => 'default',
			'WIKI_IMAGES' => 'default',
			'WIKI_TEMPLATES' => 'default'
		];

		/**
		 * Paths and urls
		 */
		$this->_settings['path'] = [
			'BASE_PATH' => '/var/www/html/DraiWiki/',
			'BASE_URL' => 'http://localhost/DraiWiki/'
		];
	}

	public function read($category, $key) {
		if (!empty($this->_settings[$category][$key]))
			return $this->_settings[$category][$key];
		else
			return null;
	}
}