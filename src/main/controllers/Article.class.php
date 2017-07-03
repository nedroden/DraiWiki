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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\{AppHeader, Article as Model};

class Article extends AppHeader {

    private $_model, $_route, $_view;

    private $_errors = [];

    public function __construct(?string $title, bool $isHomepage = false) {
        $this->loadConfig();

        $this->_model = new Model($title, $isHomepage);
        $this->_route = Registry::get('route');

        if (!empty($_POST))
            $this->handleEditRequest();

        $this->_model->setIsEditing($this->areWeEditing());
        $this->setTitle($this->_model->getTitle());

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet($this->_model->determineView(), $data, false);
    }

    private function handleEditRequest() : void {
        $this->_model->handlePostRequest();
        $this->_model->validate($this->_errors);

        if (empty($this->_errors)) {
            $this->_model->update();
            $this->redirectTo($this->config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle());
        }
    }

    /**
     * If we are editing, we need to tell so to the model. If we don't the model will
     * get mad and divorce us. Not good! :(
     * @return bool This tells us if we're editing. Yep, really!
     */
    private function areWeEditing() : bool {
        return !empty($this->_route->getParams()['action']) && $this->_route->getParams()['action'] == 'edit';
    }

    public function display() : void {
        echo $this->_view;
    }

    public function getSidebarItems() : array {
        return [
            'article' => [
                'label' => 'current_article',
                'visible' => true,
                'items' => [
                    'view' => [
                        'label' => 'view_article',
                        'href' => $this->config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle(),
                        'visible' => true
                    ],
                    'edit' => [
                        'label' => 'edit_article',
                        'href' => $this->config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/edit',
                        'visible' => true
                    ]
                ]
            ]
        ];
    }
}