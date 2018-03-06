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

namespace DraiWiki\src\auth\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\{AccountActivation as Model};
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class AccountActivation extends AppHeader {

    private $_route, $_model, $_view, $_errors;

    public function __construct() {
        $this->loadConfig();
        $this->loadUser();
        $this->_route = Registry::get('route');
        $this->hasSidebar = false;

        $this->_model = new Model();
        $this->_errors = [];

        $this->setTitle($this->_model->getTitle());
    }

    private function handleActivationRequest() : void {

    }

    public function canAccess() : bool {
        return self::$user->isGuest();
    }

    public function execute() : void {
        if (!empty($this->_route->getParams()['code']))
            $this->handleActivationRequest();
        else
            $this->cantProceedException = 'no_activation_code_given';
    }

    public function display() : void {
        echo $this->_view;
    }
}