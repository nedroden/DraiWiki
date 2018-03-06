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

namespace DraiWiki\src\tools\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\{AppHeader};
use DraiWiki\src\tools\models\ImageUploader as Model;

class ImageUploader extends AppHeader {

    private $_model, $_view;

    private $_errors = [];

    public function __construct() {
        $this->loadConfig();
        $this->loadUser();

        $this->_model = new Model();

        // The image uploader only requires one permission, so we might as well define it here.
        $this->requiredPermission = 'upload_images';

        if (!empty($_POST))
            $this->handlePostRequest();
    }

    private function handlePostRequest() : void {
        $this->_model->validate($this->_errors);

        if (empty($this->_errors)) {
            $this->_model->upload($this->_errors);

            if (empty($this->_errors))
                $this->redirectTo(self::$config->read('url') . '/index.php/resources');
        }
    }

    public function execute() : void {
        $this->setTitle($this->_model->getTitle());

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet('imageuploader', $data, false);
    }

    public function display() : void {
        echo $this->_view;
    }
}