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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\external\modules\Hook;
use DraiWiki\src\core\controllers\{QueryFactory, Registry};
use DraiWiki\src\errors\FatalError;
use DraiWiki\src\main\models\Locale as Model;
use SimpleXMLElement;

class Locale {

    private $_config;
    private $_user;

    private $_strings;
    private $_loadedFiles;

    private $_path;
    private $_model;

    public const FALLBACK_LOCALE = 'en_US';

    public function __construct() {
        $this->_config = Registry::get('config');
        $this->_user = Registry::get('user');
        $this->_loadedFiles = [];

        $localeLoadId = $this->_user->getLocaleID();
        $preferredLanguage = $this->detectLanguageSwitch();
        $this->_model = new Model($preferredLanguage ?? $localeLoadId);

        $this->loadModuleLocales();
    }

    public function loadFile(string $filename, ?string $path = null) : void {
        if (in_array($filename, $this->_loadedFiles))
            return;

        $path = $path ?? $this->_config->read('path');

        if (file_exists($file = $path . '/locales/' . $this->_model->getCode() . '/' . $filename . '.locale.php'))
            $result = require_once $file;
        else if ($this->_code != self::FALLBACK_LOCALE && file_exists($file = $path . '/locales/' . self::FALLBACK_LOCALE . '/' . $filename . '.locale.php'))
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

    private function detectLanguageSwitch() : ?int {
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
                return (int) $record['id'];
        }

        return null;
    }

    private function loadModuleLocales() : void {
        $localeFiles = [];
        Hook::callAll('locale', $localeFiles);

        foreach ($localeFiles as $localeFile) {
            $parts = explode('.', $localeFile);

            // @todo Replace with decent error message
            if (count($parts) != 2)
                die('Invalid locale loading call');

            $moduleLocalePath = $this->_config->read('path') . '/modules/' . $parts[0];
            $this->loadFile($parts[1], $moduleLocalePath);
        }
    }

    public function hasLoadedFile(string $filename) : bool {
        return isset($this->_strings[$filename]);
    }

    public function getCurrentLocaleInfo() : Model {
        return $this->_model;
    }
}