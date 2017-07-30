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
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\{ModelHeader, Table};

class SysInfo extends ModelHeader {

    private $_table;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        $this->_table = [];
    }

    private function createTable() : void {
        $columns = [
            'key',
            'value'
        ];

        $table = new Table('management', $columns, $this->generate());
        $table->setID('user_list');
        $table->setType('info_table');

        $table->create();
        $this->_table = $table->returnTable();
    }

    public function prepareData() : array {
        $this->createTable();

        return [
            'table' => $this->_table
        ];
    }

    private function generate() : array {
        return [
            [self::$locale->read('management', 'server_software'), $_SERVER['SERVER_SOFTWARE']],
            [self::$locale->read('management', 'server_operating_system'), php_uname('s')],
            [self::$locale->read('management', 'server_architecture'), php_uname('m')],
            [self::$locale->read('management', 'php_version'), phpversion()],
            [self::$locale->read('management', 'db_version'), $this->getMysqlVersion()],
            [self::$locale->read('management', 'loaded_extensions'), implode(', ', get_loaded_extensions())],
            [self::$locale->read('management', 'draiwiki_version'), Main::WIKI_VERSION],
            [self::$locale->read('management', 'default_locale'), self::$config->read('locale')],
            [self::$locale->read('management', 'default_templates'), self::$config->read('templates')],
            [self::$locale->read('management', 'default_skins'), self::$config->read('skins')],
            [self::$locale->read('management', 'default_images'), self::$config->read('images')]
        ];
    }

    private function getMysqlVersion() : string {
        $query = QueryFactory::produce('select', '
            SELECT VERSION() as db_version
        ');

        $result = $query->execute();

        foreach ($result as $record)
            return $record['db_version'];

        return self::$locale->read('management', 'unknown');
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'sysinfo_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'detailed_system_information');
    }
}