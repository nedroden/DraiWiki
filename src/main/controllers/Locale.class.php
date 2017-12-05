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
	private $_continent;
	private $_author;
	private $_softwareVersion;
	private $_localeVersion;
	private $_copyright;
	private $_user;

	private $_strings;
	private $_loadedFiles;

	public const FALLBACK_LOCALE = 'en_US';

	public function __construct(?int $localeID = null, bool $load = true) {
		$this->_config = Registry::get('config');
		$this->_user = Registry::get('user');
		$this->_loadedFiles = [];

		if ($load) {
            $infoFile = $this->loadLocaleInfo($localeID);
            $this->parseInfoFile($infoFile);
        }
        else
            $this->_id = 0xFF;

		$this->loadFile('main');
		$this->loadFile('error');
		$this->loadFile('script');
	}

	public function loadFile(string $filename) : void {
	    if (in_array($filename, $this->_loadedFiles))
	        return;

		if (file_exists($file = $this->_config->read('path') . '/locales/' . $this->_code . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else if ($this->_code != self::FALLBACK_LOCALE && file_exists($file = $this->_config->read('path') . '/locales/' . self::FALLBACK_LOCALE . '/' . $filename . '.locale.php'))
			$result = require_once $file;
		else
			die('Requested locale file not found.');

		$this->_strings[$filename] = $result;
		$this->_loadedFiles[] = $filename;
	}

	public function read(string $section, string $key, bool $return = true, bool $returnNull = false) : ?string {
		if ($return && !empty($this->_strings[$section][$key]))
			return $this->_strings[$section][$key];
		else if (!$return && !empty($this->_strings[$section][$key]))
			echo $this->_strings[$section][$key];
		else if ($return && !$returnNull)
			return '<span class="string_not_found">String not found: ' . $section . '.' . $key . '</span>';
		else if (!$returnNull)
			echo '<span class="string_not_found">String not found ', $section, '.', $key , '</span>';

		return null;
	}

	public function replace(string $section, string $key, string $value) : void {
		$this->_strings[$section][$key] = sprintf($this->_strings[$section][$key], $value);
	}

	private function loadLocaleInfo(?int $localeID = null) : string {
	    $localeLoadID = $this->_user->getLocaleID();
	    $preferredLanguage = $this->detectLanguageSwitch();

	    if (!empty($localeID) || empty($preferredLanguage)) {
	        $query = QueryFactory::produce('select', '
	            SELECT id, `code`
	                FROM {db_prefix}locale
	                WHERE id = :locale_id
	        ');

	        $query->setParams([
	            'locale_id' => $localeID ?? $localeLoadID
            ]);

	        foreach($query->execute() as $locale) {
                $localeLoadCode = $locale['code'];
                $this->_id = $locale['id'];
            }
        }
        else
            $localeLoadCode = $preferredLanguage;

		if (!empty($localeLoadCode) && file_exists($this->_config->read('path') . '/locales/' . $localeLoadCode . '/langinfo.xml'))
			$infoFile = $localeLoadCode;
		else if (file_exists($this->_config->read('path') . '/locales/' . self::FALLBACK_LOCALE) . '/langinfo.xml') {
            $infoFile = self::FALLBACK_LOCALE;
            $this->_id = null;
        }
		else
            (new FatalError('Language files not found.'))->trigger();

		return $infoFile ?? '';
	}

	public function parseInfoFile(string $locale) : void {
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
		$this->_continent = $info->continent;

		if (empty($this->_id))
            $this->setLocaleID();
	}

	private function setLocaleID() : void {
        $query = QueryFactory::produce('select', '
            SELECT id, `code`
                FROM {db_prefix}locale
                WHERE `code` = :locale_code
        ');

        $query->setParams([
            'locale_code' => $this->_code
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

    private function detectLanguageSwitch() : ?string {
        if (!empty($_SESSION[$this->_config->read('session_name') . '_locale_pref']) && !empty($locale = $_SESSION[$this->_config->read('session_name') . '_locale_pref']['locale_code'])) {
            if (strlen($locale) != 5 || !preg_match('/([a-zA-Z]{2})\_([a-zA-Z]{2})/', $locale))
                return null;

            $query = QueryFactory::produce('select', '
                SELECT id
                    FROM {db_prefix}locale
                    WHERE `code` = :lang_code
            ');

            $query->setParams([
                'lang_code' => $locale
            ]);

            foreach ($query->execute() as $record)
                return $locale;
        }

	    return null;
    }

    public function install() : ?string {
	    if (empty($this->_code))
	        return 'no_locale_code';

	    $query = QueryFactory::produce('select', '
	        SELECT code
	            FROM {db_prefix}locale
	            WHERE code = :code
	    ');

	    $query->setParams([
	        'code' => $this->_code
        ]);

	    if (count($query->execute()) >= 1)
	        return 'locale_exists';

	    $query = QueryFactory::produce('modify', '
	        INSERT
	            INTO {db_prefix}locale (
	                code
	            )
	            
	            VALUES (
	              :code     
	            )
	    ');

	    $query->setParams([
	        'code' => $this->_code
        ]);

	    $query->execute();

	    return null;
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

	public function getContinent() : string {
	    return $this->_continent;
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