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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\{AppHeader, LocaleSwitcher as Model};

class LocaleSwitcher extends AppHeader {

    private $_model;

    public function __construct() {
        $this->loadConfig();
        $this->_model = new Model(Registry::get('route')->getParams());
    }

    public function execute() : void {
        if (!$this->_model->switchLocale())
            $this->redirectTo(self::$config->read('url'));

        $this->redirectTo($this->_model->getDestination());
    }
}