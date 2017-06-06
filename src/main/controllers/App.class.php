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

class App {

    private $_route, $_currentApp, $_appObject;

    const DEFAULT_APP = 'article';

    public function __construct() {
        $this->_route = Registry::get('route');
        $this->_currentApp = $this->_route->getApp();

        $classPath = $this->detect();
        $this->load($classPath);
    }

    private function detect() : string {
        $apps = [
            'article' => 'DraiWiki\src\main\controllers\Article'
        ];

        if (empty($apps[$this->_currentApp])) {
            $this->_currentApp = self::DEFAULT_APP;
        }

        return $apps[$this->_currentApp];
    }

    public function load($classPath) : void {
        if (class_exists($classPath)) {
            if ($this->_currentApp == 'article' && empty($this->_route->getParams()))
                $this->_appObject = new $classPath(null, true);
            else if ($this->_currentApp == 'article')
                $this->_appObject = new $classPath($this->_route->getParams()['title']);
            else
                $this->_appObject = new $classPath();
        }
        else
            die('App files not found.');
    }

    private function canAccess() : bool {
        // check for permissions here
        return true;
    }

    public function execute() : void {
        if ($this->canAccess())
            $this->_appObject->execute();
    }

    public function display() : void {
        if ($this->canAccess())
            $this->_appObject->display();

        // Display an error page
        else
            die('Yep, you really shouldn\'t be here');
    }
}
