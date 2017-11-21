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
use DraiWiki\src\main\models\{ModelHeader, Table};

class LocaleManagement extends ModelHeader {

    private $_table;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
    }

    private function createTable() : void {
        $columns = [
            'key',
            'value'
        ];

        $table = new Table('management', $columns, $this->getLocales());
        $table->setID('user_list');
        $table->setType('info_table');

        $table->create();
        $this->_table = $table->returnTable();
    }

    private function getLocales() : array {
        return [];
    }

    public function prepareData() : array {
        $this->createTable();

        return [
            'table' => $this->_table
        ];
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'locales_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'locale_management');
    }
}