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
 * This class is used for loading the correct language files.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\models;

use DraiWiki\src\main\controllers\Main;

class Locale {

	private $_language, $_strings, $_files;

	private static $_instance;

	private function __construct() {
		$this->_language = Main::$config->read('wiki', 'WIKI_LOCALE');
	}

	public static function instantiate() {
		if (self::$_instance == null)
			self::$_instance = new self();

		return self::$_instance;
	}

	public function loadFile($className) {
		if (in_array($className, $this->_files))
			return;

		if (!$this->_language == 'en_US' && file_exists(Main::$config->read('path', 'BASE_PATH') . 'lang/' . $this->_language . '/' . $className . '.language.php'))
			require_once Main::$config->read('path', 'BASE_PATH') . 'lang/' . $this->_language . '/' . $className . '.language.php';
		else if (Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $className . '.language.php')
			require_once Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $className . '.language.php';
		else
			die('Could not load language file.');

		$this->loadLanguage($className);
		$this->_files[] = $className;
	}

	private function loadLanguage($className) {
		$strings = new $className();
	}
}