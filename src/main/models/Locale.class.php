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

	private $_language, $_strings, $_files = [];

	private static $_instance;

	private function __construct() {
		$this->_language = Main::$config->read('wiki', 'WIKI_LOCALE');
		$this->loadFile('Index');
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
			$this->_strings[lcfirst($className)] = require_once Main::$config->read('path', 'BASE_PATH') . 'lang/' . $this->_language . '/' . $className . '.language.php';
		else if (Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $className . '.language.php')
			$this->_strings[lcfirst($className)] = require_once Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $className . '.language.php';
		else
			die('Could not load language file.');

		$this->_files[] = $className;
	}

	public function read($category, $key, $return = true) {
		if ($return && !empty($this->_strings[$category][$key]))
			return $this->_strings[$category][$key];
		else if (!$return && !empty($this->_strings[$category][$key]))
			echo $this->_strings[$category][$key];
		else if ($return)
			return '<span class="stringNotFound">String not found: ' . $category . '.' . $key . '</span>';
		else
			echo '<span class="stringNotFound">String not found ', $category, '.', $key , '</span>';
	}
}