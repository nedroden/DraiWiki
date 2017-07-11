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

use DraiWiki\src\core\controllers\{QueryFactory, Registry};
use DraiWiki\src\errors\FatalError;
use SimpleXMLElement;

class Locale {

	private $_config;
	private $_id;
	private $_code;
	private $_name;
	private $_native;
	private $_dialect;
	private $_author;
	private $_softwareVersion;
	private $_localeVersion;
	private $_copyright;

	private $_strings;
	private $_loadedFiles;

	const DEFAULT_LOCALE = 'en_US';

	public function __construct() {
		$this->_config = Registry::get('config');
		$this->_loadedFiles = [];

		$infofile = $this->loadLocaleInfo();
		$this->parseInfoFile($infofile);

		$this->loadFile('main');
		$this->loadFile('error');
		$this->loadFile('script');
	}

	public function loadFile(string $filename) : void {
	    if (in_array($filename, $this->_loadedFiles))
	        return;

		if (file_exists($file = $this->_config->read('path') . '/locales/' . $this->_code . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else if ($this->_code != self::DEFAULT_LOCALE && file_exists($file = $this->_config->read('path') . '/locales/' . self::DEFAULT_LOCALE . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else
			die('Requested locale file not found.');

		$this->_strings[$filename] = $result;
		$this->_loadedFiles[] = $filename;
	}

	public function read(string $section, string $key, bool $return = true) : ?string {
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

	public function replace(string $section, string $key, string $value) : void {
		$this->_strings[$section][$key] = sprintf($this->_strings[$section][$key], $value);
	}

	private function loadLocaleInfo() : string {
		if (file_exists($this->_config->read('path') . '/locales/' . $this->_config->read('locale') . '/langinfo.xml'))
			$infofile = $this->_config->read('locale');
		else if ($this->_config->read('locale') != self::DEFAULT_LOCALE && file_exists($this->_config->read('path') . '/locales/' . self::DEFAULT_LOCALE) . '/langinfo.xml')
			$infofile = self::DEFAULT_LOCALE;
		else
            (new FatalError('Language files not found.'))->trigger();

		return $infofile;
	}

	private function parseInfoFile(string $locale) : void {
		if (!function_exists('simplexml_load_file'))
			die('SimpleXML extension not found.');

		$parsedFile = simplexml_load_file($this->_config->read('path') . '/locales/' . $locale . '/langinfo.xml', null, LIBXML_NOWARNING);

		if (!$parsedFile)
			die('Couldn\'t parse locale info.');

		$this->setLanguageInfo($parsedFile);
	}

	private function setLanguageInfo(SimpleXMLElement $info) : void {
		$this->_code = $info->code;
		$this->_name = $info->name;
		$this->_native = $info->native;
		$this->_dialect = $info->dialect;
		$this->_author = $info->author;
		$this->_softwareVersion = $info->software_version;
		$this->_localeVersion = $info->locale_version;
		$this->_copyright = $info->copyright;

        $this->setLocaleID();
	}

	private function setLocaleID() : void {
        $query = QueryFactory::produce('select', '
            SELECT id, `code`
                FROM {db_prefix}locale
                WHERE `code` = :locale_code
        ');

        $query->setParams([
            'locale_code' => $this->getCode()
        ]);

        $result = $query->execute();

        foreach ($result as $locale) {
            $this->_id = $locale['id'];
            return;
        }

        (new FatalError('Call to non-existing locale. Did you run the installer?'))->trigger();
    }

    public function getHomepageID() : int {
	    $query = QueryFactory::produce('select', '
	        SELECT article_id
	            FROM {db_prefix}homepage
	            WHERE locale_id = :locale
	            LIMIT 1
	    ');

	    $query->setParams([
	        'locale' => $this->_id
        ]);

	    $result = $query->execute();

	    $this->loadFile('article');

	    $homepage = 0;
        foreach ($result as $article) {
            if (!is_numeric($article['article_id']))
                (new FatalError($this->read('error', 'homepage_id_not_a_number')))->trigger();

            $homepage = $article['article_id'];
        }

        if (count($result) == 0 || $homepage == 0)
            (new FatalError($this->read('error', 'no_homepage_found')))->trigger();

        return $homepage;
    }

	public function getID() : int {
	    return $this->_id;
    }

	public function getCode() : string {
		return $this->_code;
	}

	public function getName() : string {
		return $this->_name;
	}

	public function getNative() : string {
		return $this->_native;
	}

	public function getDialect() : string {
		return $this->_dialect;
	}

	public function getAuthor() : string {
		return $this->_author;
	}

	public function getSoftwareVersion() : string {
		return $this->_softwareVersion;
	}

	public function getLocaleVersion() : string {
		return $this->_localeVersion;
	}

	public function getCopyright() : string {
		return $this->_copyright;
	}
}