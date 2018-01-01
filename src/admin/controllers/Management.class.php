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

namespace DraiWiki\src\admin\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\admin\models\Management as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class Management extends AppHeader {

    private $_model, $_gui, $_route, $_currentSubApp, $_subApp;

    public function __construct() {
        $this->checkForAjax();

        $this->loadConfig();
        $this->loadUser();
        $this->_route = Registry::get('route');

        $this->_currentSubApp = $this->_route->getParams()['subapp'] ?? 'dashboard';
        $this->loadSubApp();

        // The management panel has its own sidebar
        $this->hasSidebar = false;

        $this->_gui = Registry::get('gui');

        $this->_model = new Model();
        $this->_model->setActiveMenuItem($this->getActiveMenuItem());
        $this->_model->generateSidebar();

        // We have our own templates
        $this->ignoreTemplates = 'both';

        // Regular permission errors don't work, so if a user doesn't have access to the management panel, just redirect to the wiki index
        if (!self::$user->hasPermission('manage_site')) {
            header('Location: ' . self::$config->read('url') . '/index.php');
            die;
        }
    }

    private function loadSubApp() : void {
        $appClass = $this->detectApp();
        $this->_subApp = new $appClass();
    }

    private function detectApp() : string {
        $apps = [
            'dashboard' => 'DraiWiki\src\admin\controllers\Dashboard',
            'edituser' => 'DraiWiki\src\admin\controllers\ProfileManager',
            'generalmaintenance' => 'DraiWiki\src\admin\controllers\GeneralMaintenance',
            'locales' => 'DraiWiki\src\admin\controllers\LocaleManagement',
            'manageuploads' => 'DraiWiki\src\admin\controllers\UploadManagement',
            'settings' => 'DraiWiki\src\admin\controllers\SettingsPage',
            'sysinfo' => 'DraiWiki\src\admin\controllers\SysInfo',
            'users' => 'DraiWiki\src\admin\controllers\UserManagement'
        ];

        if (empty($apps[$this->_currentSubApp])) {
            $this->_currentSubApp = 'dashboard';
        }

        return $apps[$this->_currentSubApp];
    }

    public function execute() : void {
        $this->_model->setTitle($this->_subApp->getTitle());
        $this->_subApp->execute();
    }

    public function display() : void {
        /**
         * @todo Move to model
         */
        $additionalData = [
            'wiki_name' => self::$config->read('wiki_name'),
            'skin_url' => $this->_gui->getSkinUrl(),
            'image_url' => $this->_gui->getImageUrl(),
            'copyright' => $this->_gui->getCopyright(),
            'url' => self::$config->read('url'),
            'page_description' => $this->_subApp->getPageDescription()
        ];

        if (!$this->ajax) {
            echo $this->_gui->parseAndGet('admin_header', $this->_model->prepareData() + $additionalData, false);
            $this->_subApp->display();
            echo $this->_gui->parseAndGet('admin_footer', $this->_model->prepareData() + $additionalData, false);
        }

        else
            $this->_subApp->printJSON();
    }

    private function getActiveMenuItem() : string {
        return $this->_currentSubApp != 'settings' ? $this->_currentSubApp : 'settings_' . ($this->_route->getParams()['section'] ?? 'general');
    }
}