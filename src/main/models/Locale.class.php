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

use DraiWiki\src\database\controllers\Query;
use DraiWiki\src\main\controllers\Main;

class Locale {

	private $_language, $_strings, $_files = [];

	private static $_instance;

	private function __construct() {
		$this->_language = $this->loadInfo(Main::$config->read('wiki', 'WIKI_LOCALE'));

		$this->loadFile('index');
		$this->loadFile('error');
	}

	public static function instantiate() {
		if (self::$_instance == null)
			self::$_instance = new self();

		return self::$_instance;
	}

	public function loadFile($category) {
		$fileName = ucfirst($category);
		$category = lcfirst($category);

		if (in_array($category, $this->_files))
			return;

		if ($this->_language['code'] != 'en_US' && file_exists(Main::$config->read('path', 'BASE_PATH') . 'lang/' . $this->_language['code'] . '/' . $fileName . '.language.php'))
			$this->_strings[$category] = require_once Main::$config->read('path', 'BASE_PATH') . 'lang/' . $this->_language['code'] . '/' . $fileName . '.language.php';
		else if (Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $fileName . '.language.php')
			$this->_strings[$category] = require_once Main::$config->read('path', 'BASE_PATH') . 'lang/en_US/' . $fileName . '.language.php';
		else
			die('Could not load language file.');

		$this->_files[] = $category;
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

		return null;
	}

	public function getLanguage() {
		return $this->_language;
	}

	private function loadInfo($localeID) {
		$query = new Query('
			SELECT ID, code, homepage
				FROM {db_prefix}locales
				WHERE code = :locale
				LIMIT 1
		');

		$query->setParams(['locale' => $localeID]);
		$result = $query->execute();

		$localeInfo = [];
		foreach ($result as $locale) {
			foreach ($locale as $key => $value) {
				$localeInfo[$key] = $value;
			}
		}

		return $localeInfo;
	}
}