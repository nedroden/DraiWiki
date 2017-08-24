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

use DraiWiki\src\auth\models\AccountManager as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class AccountManager extends AppHeader {

    private $_model, $_view, $_errors, $_routeParams, $_success;

    public function __construct() {
        $this->loadUser();

        $this->hasSidebar = false;

        $this->_model = new Model();
        $this->_errors = [];

        $this->_routeParams = Registry::get('route')->getParams();
        $this->_success = false;

        $sections = ['settings'];
        if (!in_array($this->_routeParams['section'], $sections)) {
            $this->cantProceedException = 'section_not_found';
            return;
        }

        $this->setTitle($this->_model->getTitle());
    }

    public function handlePostRequest() : void {
        $this->_model->validateRequest($this->_errors);

        if (empty($this->_errors)) {
            $this->_model->updateUser($this->_errors);

            if (empty($this->_errors))
                $this->_success = true;
        }
    }

    public function canAccess() : bool {
        return !self::$user->isGuest();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }

    public function execute() : void {
        $result = $this->_model->loadAccount($this->_routeParams['id'] ?? null);

        if ($this->_routeParams['section'] == 'settings') {
            if (!empty($result)) {
                $this->cantProceedException = $result;
                return;
            }

            if (!empty($_POST))
                $this->handlePostRequest();

            $this->_view = Registry::get('gui')->parseAndGet('account_settings', array_merge($this->_model->prepareData(), ['errors' => $this->_errors, 'success' => $this->_success]), false);
        }
        else
            $this->cantProceedException = 'section_not_found';
    }

    public function display() : void {
        echo $this->_view;
    }
}