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

use DraiWiki\src\main\models\AppHeader;
use DraiWiki\src\main\models\Random as Model;

class Random extends AppHeader {

    private $_model;

    public function __construct() {
        $this->loadConfig();
        $this->_model = new Model();
        $this->redirect($this->_model->getArticle());
    }

    private function redirect(?string $title) : void {
        $url = self::$config->read('url') . (!empty($title) ? '/index.php/article/' . $title : '');
        header('Location: ' . $url);
        die;
    }
}