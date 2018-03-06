<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\external\modules\Hook;
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

    public function __construct(?int $localeID = null) {
        $this->_config = Registry::get('config');
        $this->_loadedFiles = [];

        $infoFile = $this->loadLocaleInfo($localeID);
        $this->parseInfoFile($infoFile);
    }

    private function loadLocaleInfo(?int $localeID = null) : string {
        $query = QueryFactory::produce('select', '
            SELECT id, `code`
                FROM {db_prefix}locale
                WHERE id = :locale_id
        ');

        $query->setParams([
            'locale_id' => $localeID
        ]);

        foreach($query->execute() as $locale) {
            $localeLoadCode = $locale['code'];
            $this->_id = $locale['id'];
        }

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

    public function isDefault() : bool {
        return $this->_id == $this->_config->read('locale');
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