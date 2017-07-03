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

namespace DraiWiki\src\auth\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\Login as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class Login extends AppHeader {

    private $_model, $_view, $_errors;

    public function __construct() {
        $this->loadConfig();
        $this->hasSidebar = false;

        $this->_model = new Model();
        $this->_errors = [];

        $this->setTitle($this->_model->getTitle());

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet('login', $data, false);
    }

    public function display() : void {
        echo $this->_view;
    }
}