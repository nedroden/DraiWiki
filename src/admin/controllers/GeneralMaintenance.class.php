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

use DraiWiki\src\admin\models\GeneralMaintenance as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class GeneralMaintenance extends AppHeader {

    private $_model, $_view, $_route, $_errors, $_submitted;

    public function __construct() {
        $this->_route = Registry::get('route');

        $this->_model = new Model();

        $this->_errors = [];

        // 0: nothing submitted, 1: success, 2: failure
        $this->_submitted = false;
    }

    public function execute() : void {
        $this->_view = Registry::get('gui')->parseAndGet('admin_general_maintenance', $this->_model->prepareData() + ['errors' => $this->_errors], false);
    }

    public function display() : void {
        echo $this->_view;
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }
}