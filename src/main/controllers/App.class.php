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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\errors\{AccessError, CantProceedException};
use DraiWiki\src\main\models\DebugBarWrapper;

class App {

    private $_route, $_currentApp, $_appObject, $_appInfo, $_locale;
    private $_cantProceed;

    const DEFAULT_APP = 'article';

    public function __construct() {
        $this->_route = Registry::get('route');
        $this->_currentApp = $this->_route->getApp();

        $this->_locale = Registry::get('locale');

        $classPath = $this->detect();
        $this->load($classPath);

        $this->_cantProceed = false;
    }

    private function detect() : string {
        $apps = [
            'account'       => 'DraiWiki\src\auth\controllers\AccountManager',
            'activate'      => 'DraiWiki\src\auth\controllers\Activate',
            'article'       => 'DraiWiki\src\main\controllers\Article',
            'changelocale'  => 'DraiWiki\src\main\controllers\LocaleSwitcher',
            'findarticle'   => 'DraiWiki\src\tools\controllers\ArticleFinder',
            'imageviewer'   => 'DraiWiki\src\tools\controllers\ImageViewer',
            'imageupload'   => 'DraiWiki\src\tools\controllers\ImageUploader',
            'login'         => 'DraiWiki\src\auth\controllers\Login',
            'logout'        => 'DraiWiki\src\auth\controllers\Logout',
            'management'    => 'DraiWiki\src\admin\controllers\Management',
            'random'        => 'DraiWiki\src\main\controllers\Random',
            'register'      => 'DraiWiki\src\auth\controllers\Registration'
        ];

        if (empty($apps[$this->_currentApp])) {
            $this->_currentApp = self::DEFAULT_APP;
        }

        DebugBarWrapper::report('App detected: ' . $this->_currentApp);

        return $apps[$this->_currentApp];
    }

    public function load(string $classPath) : void {
        if (class_exists($classPath)) {
            if ($this->_currentApp == 'article' && empty($this->_route->getParams()))
                $this->_appObject = new $classPath(null, true);
            else if ($this->_currentApp == 'article')
                $this->_appObject = new $classPath($this->_route->getParams()['title']);
            else
                $this->_appObject = new $classPath();

            DebugBarWrapper::report('App loaded');
        }
        else
            die('App files not found.');
    }

    private function canAccess() : bool {
        return $this->_appObject->canAccess();
    }

    public function execute() : void {
        if ($this->canAccess()) {
            $this->_appObject->execute();

            if ($this->hasCantProceedException()) {
                $this->_appObject->setTitle($this->_locale->read('error', 'something_went_wrong'));
                $this->_cantProceed = true;
            }
        }
        else
            $this->_appObject->setTitle($this->_locale->read('error', 'access_denied'));
    }

    public function hasCantProceedException() : bool {
        return !empty($this->_appObject->getCantProceedException());
    }

    public function display() : void {
        if ($this->canAccess() && !$this->_cantProceed) {
            if (!$this->_appInfo['ajax'] || $this->_currentApp == 'management') {
                if ($this->_appInfo['has_sidebar'])
                    Registry::get('gui')->displaySidebar($this->_appObject->getSidebarItems());

                $this->_appObject->display();
            }
            else
                $this->_appObject->printJSON();
        }
        else if ($this->_cantProceed) {
            (new CantProceedException($this->_locale->read('error', $this->_appObject->getCantProceedException())))->trigger();
        }
        else {
            if ($this->_currentApp != 'management')
                Registry::get('gui')->displaySidebar($this->_appObject->getSidebarItems());

            (new AccessError())->trigger();
        }
    }

    public function getHeaderContext() : array {
        $info = $this->_appObject->getAppInfo();

        $this->_appInfo = [
            'title' => $this->canAccess() ? $info['title'] : $this->_locale->read('main', 'access_denied'),
            'has_sidebar' => $info['has_sidebar'],
            'ignore_templates' => $this->_appObject->getIgnoreTemplates(),
            'header' => $this->_appObject->getAdditionalHeaders(),
            'ajax' => $info['ajax']
        ];

        return $this->_appInfo;
    }

    public function getCurrentApp() : string {
        return $this->_currentApp;
    }
}
