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

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\main\controllers\Locale;
use DraiWiki\src\main\models\{ModelHeader, Table};

class LocaleManagement extends ModelHeader {

    private $_installedLocalesTable;
    private $_installedLocaleCodes;
    private $_locales;
    private $_missingLocales;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        $this->_missingLocales = [];

        self::$locale->loadFile('management');
    }

    public function createInstalledLocalesTable() : void {
        $columns = [
            'id',
            'code',
            'native',
            'dialect',
            'software_version',
            'locale_version',
            'actions'
        ];

        $this->_installedLocaleCodes = [];
        $this->_locales = $this->getLocales();

        $table = new Table('management', $columns, $this->_locales);
        $table->setID('user_list');

        $table->create();
        $this->_installedLocalesTable = $table->returnTable();
    }

    private function getLocales() : array {
        $query = QueryFactory::produce('select', '
            SELECT id, code
                FROM {db_prefix}locale
        ');

        $result = $query->execute();
        $locales = [];

        // @todo Throw error message
        if (count($result) == 0) {
            die('Could not load locales');
        }

        $localePath = self::$config->read('path') . '/locales/';

        foreach ($result as $locale) {
            if (!file_exists($localePath . '/' . $locale['code'] . '/langinfo.xml')) {
                $this->_missingLocales[] = $locale['code'];
                continue;
            }

            $obj = self::$locale->getID() == $locale['id'] ? self::$locale : new Locale($locale['id']);

            $locales[] = [
                $obj->getID(),
                $obj->getCode(),
                $obj->getNative(),
                $obj->getDialect(),
                $obj->getSoftwareVersion(),
                $obj->getLocaleVersion(),

                // @todo Replace with id
                $this->generateActionButtons($obj->getCode())
            ];

            $this->_installedLocaleCodes[] = $obj->getCode();
        }

        uasort($locales, function(array $a, array $b) {
            return $a[2] <=> $b[2];
        });

        return $locales;
    }

    public function prepareData() : array {
        return [
            'installed_locales' => $this->_installedLocalesTable,
            'uninstalled_links' => $this->getUninstalledLocaleLinks()
        ];
    }

    private function getUninstalledLocales() : array {
        $localePath = self::$config->read('path') . '/locales';
        $directories = scandir($localePath);
        $locales = [];

        foreach ($directories as $directory) {
            if (is_dir($localePath . '/' . $directory) && !in_array($directory, $this->_installedLocaleCodes)) {
                if ($directory == '.' || $directory == '..')
                    continue;
                else if (!file_exists($localePath . '/' . $directory . '/langinfo.xml'))
                    continue;

                $locale = new Locale(0, false);
                $locale->parseInfoFile($directory);
                $locales[] = $locale;
            }
        }

        return $locales;
    }

    private function getUninstalledLocaleLinks() : array {
        $uninstalledLocales = $this->getUninstalledLocales();
        $links = [];

        foreach ($uninstalledLocales as $uninstalledLocale) {
            $links[] = sprintf(
                self::$locale->read('management', 'uninstalled_locale_detected'),
                $uninstalledLocale->getNative(),
                self::$config->read('url') . '/index.php/management/locales/add/' . $uninstalledLocale->getCode()
            );
        }

        return $links;
    }

    public function installLocale(string $code) : ?string {
        if (!preg_match('/([a-zA-Z]{2})\_([a-zA-Z]{2})/', $code))
            return 'invalid_locale_code';
        else if (in_array($code, $this->_installedLocaleCodes))
            return 'locale_exists';
        else if (!file_exists(self::$config->read('path') . '/locales/' . $code . '/langinfo.xml'))
            return 'no_locale_files_found';

        $query = QueryFactory::produce('modify', '
            INSERT
                INTO {db_prefix}locale (
                    code
                )
                
                VALUES (
                    :code
                );
                
            INSERT 
                INTO {db_prefix}article (
                    title, locale_id, `status`
                )
                VALUES (
                    :title, LAST_INSERT_ID(), :status_nr
                );

            INSERT
                INTO {db_prefix}article_history (
                    article_id, user_id, body
                )
                VALUES (
                    LAST_INSERT_ID(),
                    :user_id,
                    :body
                );
        ');

        $query->setParams([
            'code' => $code,
            'title' => self::$locale->read('management', 'new_homepage_title'),
            'status_nr' => 1,
            'user_id' => self::$user->getID(),
            'body' => self::$locale->read('management', 'new_homepage_body')
        ]);

        $query->execute();

        return null;
    }

    public function deleteLocale(string $code) : ?string {
        if (!in_array($code, $this->_installedLocaleCodes))
            return 'locale_does_not_exist';
        else if ($code == Locale::FALLBACK_LOCALE)
            return 'cannot_delete_fallback_locale';

        $query = QueryFactory::produce('modify', '
            DELETE
                FROM {db_prefix}locale
                WHERE code = :code
        ');

        // @todo Remove articles associated with the locale that is being deleted

        $query->setParams([
            'code' => $code
        ]);

        $query->execute();

        return null;
    }

    private function generateActionButtons(string $code) : string {
        $actions = ['delete'];
        $buttons = '';

        foreach ($actions as $action) {
            $url = self::$config->read('url') . '/index.php/management/locales/' . $action . '/' . $code;
            $buttons .= sprintf('[<a href="javascript:void:(0);" onclick="requestConfirm(\'%s\')">%s</a>]', $url, self::$locale->read('management', $action));
        }

        return $buttons;
    }

    public function handleMissingLocales(array &$errors) : void {
        if (!empty($this->_missingLocales))
            $errors[] = 'missing_locale_files';
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'locales_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'locale_management');
    }
}