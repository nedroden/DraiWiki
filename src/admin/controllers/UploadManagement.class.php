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

use DraiWiki\src\admin\models\{SettingsPage, UploadManagement as Model};
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class UploadManagement extends AppHeader {

    private $_model, $_view, $_settingsPage;

    public function __construct() {
        $this->_model = new Model();
        $this->_settingsPage = new SettingsPage('uploads');

        $this->_settingsPage->loadSettings();
        $this->_settingsPage->generateTable();
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }

    public function execute() : void {
        $this->_view = Registry::get('gui')->parseAndGet('admin_uploads', array_merge(['settings' => $this->_settingsPage->prepareData()['table']], $this->_model->prepareData()), false);
    }

    public function display() : void {
        echo $this->_view;
    }
}