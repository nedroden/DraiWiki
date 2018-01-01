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

use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\{ModelHeader};

class GeneralMaintenance extends ModelHeader {

    private $_actions;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        self::$locale->loadFile('management');

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
            'generate_new_salt' => [
                'title' => 'generate_new_salt',
                'description' => 'generate_new_salt_description',
                'href' => self::$config->read('url') . '/index.php/management/generalmaintenance/removeoldsessions'
            ]
        ];

        $color = 0;
        foreach ($this->_actions as &$action) {
            $action['class'] = ++$color;
            $action['title'] = self::$locale->read('management', $action['title']);
            $action['description'] = self::$locale->read('management', $action['description']);

            if ($color > 5)
                $color = 0;
        }
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'general_maintenance');
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'general_maintenance_description');
    }
}