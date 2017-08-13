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

namespace DraiWiki\src\admin\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\admin\models\SettingsPage as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class SettingsPage extends AppHeader {

    private $_model, $_view, $_route, $_errors, $_submitted;

    public function __construct() {
        $this->_route = Registry::get('route');
        $this->_model = new Model($this->_route->getParams()['section'] ?? 'general');

        $this->_errors = [];

        // 0: nothing submitted, 1: success, 2: failure
        $this->_submitted = false;
    }

    public function execute() : void {
        $this->_model->loadSettings();

        if (!empty($_POST))
            $this->handleSettingsUpdateRequest();

        $this->_model->generateTable();

        $this->_view = Registry::get('gui')->parseAndGet('admin_settings', $this->_model->prepareData() + ['submitted' => $this->_submitted, 'errors' => $this->_errors], false);
    }

    public function display() : void {
        echo $this->_view;
    }

    private function handleSettingsUpdateRequest(): void {
        $this->_model->validateSettings($this->_errors);
        $this->_submitted = true;

        if (empty($this->_errors)) {
            $this->_model->updateSettings();

            if (empty($this->_errors)) {
                return;
            }
        }
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }
}