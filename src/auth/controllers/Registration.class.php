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

use DraiWiki\src\auth\models\Registration as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class Registration extends AppHeader {

    private $_model, $_view, $_errors;

    public function __construct() {
        $this->loadConfig();
        $this->hasSidebar = false;

        $this->_model = new Model();
        $this->_errors = [];

        $this->setTitle($this->_model->getTitle());

        if (!empty($_POST))
            $this->handlePostRequest();

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet('registration', $data, false);
    }

    private function handlePostRequest() : void {
        $this->_model->handlePostRequest();
        $this->_model->validate($this->_errors);

        if (empty($this->_errors)) {
            $this->_model->createUser($this->_errors);

            // We may not have had errors last time, but we should check if we've got any errors this time as well
            if (empty($this->_errors))
                $this->redirectTo($this->config->read('url') . '/index.php/login');
        }
    }

    public function display() : void {
        echo $this->_view;
    }
}