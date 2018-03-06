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
            [_localized('management.server_software'), $_SERVER['SERVER_SOFTWARE']],
            [_localized('management.server_operating_system'), php_uname('s')],
            [_localized('management.server_architecture'), php_uname('m')],
            [_localized('management.php_version'), phpversion()],
            [_localized('management.db_version'), $this->getMysqlVersion()],
            [_localized('management.loaded_extensions'), implode(', ', get_loaded_extensions())],
            [_localized('management.draiwiki_version'), Main::WIKI_VERSION],
            [_localized('management.default_locale'), self::$config->read('locale')],
            [_localized('management.default_templates'), self::$config->read('templates')],
            [_localized('management.default_skins'), self::$config->read('skins')],
            [_localized('management.default_images'), self::$config->read('images')]
        ];
    }

    private function getMysqlVersion() : string {
        $query = QueryFactory::produce('select', '
            SELECT VERSION() as db_version
        ');

        $result = $query->execute();

        foreach ($result as $record)
            return $record['db_version'];

        return _localized('management.unknown');
    }

    public function getPageDescription() : string {
        return _localized('management.sysinfo_description');
    }

    public function getTitle() : string {
        return _localized('management.detailed_system_information');
    }
}