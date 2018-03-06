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

namespace DraiWiki\src\admin\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\admin\models\UserManagement as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class UserManagement extends AppHeader {

    private $_model, $_view, $_errors;

    public function __construct() {
        $this->checkForAjax();

        if ($this->ajax)
            $this->parseAjaxRequest();

        $this->_model = new Model();
        $this->_errors = [];

        $route = Registry::get('route');
        $params = $route->getParams();

        if (!empty($params['action']) && $params['action'] == 'delete' && !empty($params['id']))
            $this->_model->deleteUser($this->_errors, $params['id']);
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }

    public function execute() : void {
        if ($this->ajax && !empty($this->parsedAJAXRequest['getlist']))
            $this->_model->setRequest('getlist');

        if (!empty($_REQUEST['start'])) {
            if (is_numeric($start = $_REQUEST['start']))
                $this->_model->loadUsers((int) $start);
            else
                $this->_model->loadUsers(0);
        }
        else
            $this->_model->loadUsers(0);

        $this->_view = Registry::get('gui')->parseAndGet('admin_users_list', array_merge($this->_model->prepareData(), ['errors' => $this->_errors]), false);
    }

    public function display() : void {
        echo $this->_view;
    }

    public function printJSON() : void {
        echo $this->_model->generateJSON();
    }
}