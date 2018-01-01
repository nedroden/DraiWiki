<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 *
 * Note: this file depends on the FastRoute routing library.
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\{AppHeader, About as Model};

class About extends AppHeader {

    private $_model;
    private $_view;

    public function __construct() {
        $this->_model = new Model();
    }

    public function execute() : void {
        $this->setTitle($this->_model->getTitle());
        $this->_view = Registry::get('gui')->parseAndGet('about', $this->_model->prepareData(), false);
    }

    public function display() : void {
        echo $this->_view;
    }

}