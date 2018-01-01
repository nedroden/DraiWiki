<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\{ActionBar, ModelHeader, Table};

class SysInfo extends ModelHeader {

    private $_table, $_actionBar;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
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

    public function createActionBar() : void {
        $items = [
            'text_format' => [
                'label' => 'text_format',
                'icon' => 'fa-copy',
                'action' => 'sysInfoToText()'
            ]
        ];

        $actionBar = new ActionBar('management', $items);
        $actionBar->create();
        $this->_actionBar = $actionBar->getBar();
    }

    public function prepareData() : array {
        $this->createTable();
        $this->createActionBar();

        return [
            'actionbar' => $this->_actionBar,
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