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
use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\{ModelHeader};

class GeneralMaintenance extends ModelHeader {

    private $_actions;

    public function __construct() {
        $this->loadConfig();

        $this->setActions();
    }

    public function prepareData() : array {
        return [
            'actions' => $this->_actions
        ];
    }

    private function setActions() : void {
        $this->_actions = [
            'remove_old_sessions' => [
                'title' => 'remove_old_sessions',
                'description' => 'remove_old_sessions_description',
                'href' => self::$config->read('url') . '/index.php/management/generalmaintenance/removeoldsessions'
            ],
            'check_version' => [
                'title' => 'check_version',
                'description' => 'check_version_description',
                'href' => 'https://draiwiki.robertmonden.com/versioncheck.php?version=' . Sanitizer::addUnderscores(Main::WIKI_VERSION)
            ],
            'empty_error_log' => [
                'title' => 'empty_error_log',
                'description' => 'empty_error_log_description',
                'href' => self::$config->read('url') . '/index.php/management/generalmaintenance/emptyerrorlog'
            ]
        ];

        $color = 0;
        foreach ($this->_actions as &$action) {
            $action['class'] = ++$color;
            $action['title'] = _localized('management.' . $action['title']);
            $action['description'] = _localized('management.' . $action['description']);

            if ($color > 5)
                $color = 0;
        }
    }

    public function removeOldSessions() : void {
        $query = QueryFactory::produce('modify', '
            DELETE FROM {db_prefix}session
                WHERE (NOW() - created_at) > :older_than
        ');

        $query->setParams(['older_than' => 3600 * 24 * 31]);
        $query->execute();
    }

    public function emptyErrorLog() : void {
        QueryFactory::produce('modify', '
            DELETE FROM {db_prefix}log_errors
        ')->execute();
    }

    public function getTitle() : string {
        return _localized('management.general_maintenance');
    }

    public function getPageDescription() : string {
        return _localized('management.general_maintenance_description');
    }
}