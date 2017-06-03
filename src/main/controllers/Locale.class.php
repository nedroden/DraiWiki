<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use SimpleXMLElement;

class Locale {

	private $_config;

	private $_code;
	private $_name;
	private $_native;
	private $_dialect;
	private $_author;
	private $_softwareVersion;
	private $_localeVersion;
	private $_copyright;

	private $_strings;

	const DEFAULT_LOCALE = 'en_US';

	public function __construct() {
		$this->_config = Registry::get('config');

		$infofile = $this->loadLocaleInfo();
		$this->parseInfoFile($infofile);

		$this->loadFile('main');
	}

	public function loadFile($filename) {
		if (file_exists($file = $this->_config->read('path') . '/locales/' . $this->_code . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else if ($this->_code != self::DEFAULT_LOCALE && file_exists($file = $this->_config->read('path') . '/locales/' . self::DEFAULT_LOCALE . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else
			die('Requested locale file not found.');

		$this->_strings[$filename] = $result;
	}

	public function read($section, $key, $return = true) {
		if ($return && !empty($this->_strings[$section][$key]))
			return $this->_strings[$section][$key];
		else if (!$return && !empty($this->_strings[$section][$key]))
			echo $this->_strings[$section][$key];
		else if ($return)
			return '<span class="string_not_found">String not found: ' . $section . '.' . $key . '</span>';
		else
			echo '<span class="string_not_found">String not found ', $section, '.', $key , '</span>';

		return null;
	}

	public function replace($section, $key, $value) {
		$this->_strings[$section][$key] = sprintf($this->_strings[$section][$key], $value);
	}

	private function loadLocaleInfo() {
		if (file_exists($this->_config->read('path') . '/locales/' . $this->_config->read('locale') . '/langinfo.xml'))
			$infofile = $this->_config->read('locale');
		else if ($this->_config->read('locale') != self::DEFAULT_LOCALE && file_exists($this->_config->read('path') . '/locales/' . self::DEFAULT_LOCALE) . '/langinfo.xml')
			$infofile = self::DEFAULT_LOCALE;
		else
			die('<h1>Language files not found</h1>');

		return $infofile;
	}

	private function parseInfoFile($locale) {
		if (!function_exists('simplexml_load_file'))
			die('SimpleXML extension not found.');

		$parsedFile = simplexml_load_file($this->_config->read('path') . '/locales/' . $locale . '/langinfo.xml', null, LIBXML_NOWARNING);

		if (!$parsedFile)
			die('Couldn\'t parse locale info.');

		$this->setLanguageInfo($parsedFile);
	}

	private function setLanguageInfo(SimpleXMLElement $info) {
		$this->_code = $info->code;
		$this->_name = $info->name;
		$this->_native = $info->native;
		$this->_dialect = $info->dialect;
		$this->_author = $info->author;
		$this->_softwareVersion = $info->software_version;
		$this->_localeVersion = $info->locale_version;
		$this->_copyright = $info->copyright;
	}

	public function getCode() {
		return $this->_code;
	}

	public function getName() {
		return $this->_name;
	}

	public function getNative() {
		return $this->_native;
	}

	public function getDialect() {
		return $this->_dialect;
	}

	public function getAuthor() {
		return $this->_author;
	}

	public function getSoftwareVersion() {
		return $this->_softwareVersion;
	}

	public function getLocaleVersion() {
		return $this->_localeVersion;
	}

	public function getCopyright() {
		return $this->_copyright;
	}
}