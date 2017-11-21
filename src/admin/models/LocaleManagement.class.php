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
    private $_locales;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
    }

    private function createInstalledLocalesTable() : void {
        $columns = [
            'id',
            'code',
            'native',
            'dialect',
            'software_version',
            'locale_version'
        ];

        $this->_locales = $this->getLocales();

        $table = new Table('management', $columns, $this->_locales);
        $table->setID('user_list');

        $table->create();
        $this->_installedLocalesTable = $table->returnTable();
    }

    private function getLocales() : array {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM {db_prefix}locale
        ');

        $result = $query->execute();
        $locales = [];

        // @todo Throw error message
        if (count($result) == 0) {
            die('Could not load locales');
        }

        foreach ($result as $locale) {
            $obj = new Locale($locale['id']);

            $locales[] = [
                $obj->getID(),
                $obj->getCode(),
                $obj->getNative(),
                $obj->getDialect(),
                $obj->getSoftwareVersion(),
                $obj->getLocaleVersion()
            ];
        }

        uasort($locales, function(array $a, array $b) {
            return $a[2] <=> $b[2];
        });

        return $locales;
    }

    public function prepareData() : array {
        $this->createInstalledLocalesTable();

        return [
            'installed_locales' => $this->_installedLocalesTable
        ];
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'locales_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'locale_management');
    }
}