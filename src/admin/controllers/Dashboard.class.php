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

use DraiWiki\src\admin\models\Dashboard as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class Dashboard extends AppHeader {

    private $_model, $_view;

    public function __construct() {
        $this->checkForAjax();

        if ($this->ajax)
            $this->parseAjaxRequest();

        $this->_model = new Model();
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }

    public function execute() : void {
        if ($this->ajax && !empty($this->parsedAJAXRequest['getrecentedits']))
            $this->_model->setRequest('getrecentedits');

        $this->_view = Registry::get('gui')->parseAndGet('admin_dashboard', $this->_model->prepareData(), false);
    }

    public function display() : void {
        echo $this->_view;
    }

    public function printJSON() : void {
        echo $this->_model->generateJSON();
    }
}