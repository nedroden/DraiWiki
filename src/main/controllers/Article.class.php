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

    private $_model, $_route, $_view, $_subApp;

    private $_errors = [];

    public function __construct(?string $title, bool $isHomepage = false) {
        $this->loadConfig();
        $this->loadUser();

        $this->_model = new Model($title, $isHomepage);
        $this->_route = Registry::get('route');

        if (!empty($this->_route->getParams()['action']))
            $this->takeActionMeasures();

        if (!empty($this->_subApp))
            $this->_model->setSubApp($this->_model->getIsEditing() && $this->canAccess('edit_articles') ? 'edit' : $this->_subApp);
    }

    private function takeActionMeasures() : void {
        switch ($this->_route->getParams()['action']) {
            case 'edit':
                $this->requiredPermission = 'edit_articles';
                $this->_subApp = 'edit';
                $this->_model->setIsEditing(true);
                break;
            case 'delete':
                $this->requiredPermission = 'soft_delete_articles';
                $this->_subApp = 'delete';
                break;
            default:
                $this->_subApp = 'unknown';
        }
    }

    private function handleEditRequest() : void {
        $this->_model->handlePostRequest();
        $this->_model->validate($this->_errors);

        if (empty($this->_errors)) {
            $this->_model->update();
            $this->redirectTo($this->config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle());
        }
    }

    private function delete() : void {
        if ($this->_model->getIsHomepage()) {
            $this->cantProceedException = 'cannot_delete_homepage';
            return;
        }

        if ($this->_model->softDelete())
            $this->redirectTo($this->config->read('url') . '/index.php');
        else
            $this->cantProceedException = 'cannot_delete_article';
    }

    public function execute() : void {
        if (!empty($_POST) && $this->_subApp == 'edit')
            $this->handleEditRequest();

        $this->setTitle($this->_model->getTitle());

        if ($this->_subApp == 'delete') {
            $this->delete();
            return;
        }

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet($this->_model->determineView(), $data, false);
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
                        'visible' => $this->user->hasPermission('edit_articles')
                    ],
                    'delete' => [
                        'label' => 'delete_article',
                        'href' => $this->config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/delete',
                        'visible' => $this->user->hasPermission('soft_delete_articles'),
                        'request_confirm' => true
                    ]
                ]
            ]
        ];
    }
}