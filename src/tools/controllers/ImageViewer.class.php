<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\tools\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;
use DraiWiki\src\tools\models\ImageViewer as Model;

class ImageViewer extends AppHeader {

    private $_route;
    private $_filename;
    private $_model;
    private $_view;

    public function __construct() {
        $this->_route = Registry::get('route');
        $this->_filename = $this->_route->getParams()['filename'];

        $this->_model = new Model($this->_filename);
    }

    public function execute() : void {
        if (!$this->_model->loadImage()) {
            $this->cantProceedException = 'image_not_found';
            return;
        }

        $this->setTitle($this->_model->getTitle());
        $this->_view = Registry::get('gui')->parseAndGet('imageview', $this->_model->prepareData(), false);
    }

    public function display() : void {
        echo $this->_view;
    }
}