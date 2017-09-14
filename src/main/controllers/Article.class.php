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
    private $_routeParams;

    public function __construct(?string $title, bool $isHomepage = false) {
        $this->loadConfig();
        $this->loadUser();

        $this->checkForAjax();
        $this->_route = Registry::get('route');

        if ($this->ajax)
            $this->parseAjaxRequest();

        $this->_routeParams = $this->_route->getParams();

        if (!empty($this->_routeParams['action']) && $this->_routeParams['action'] == 'history' && !empty($this->_routeParams['id'])) {
            $this->requiredPermission = 'view_article_history';
            $this->_routeParams['action'] = 'article';
            $this->_model = new Model($title, $isHomepage, $this->_routeParams['id']);
        }
        else
            $this->_model = new Model($title, $isHomepage);

        if (!empty($this->_routeParams['action']))
            $this->takeActionMeasures();
        else
            $this->_subApp = 'article';

        if (!empty($this->_subApp))
            $this->_model->setSubApp($this->_model->getIsEditing() && $this->canAccess('edit_articles') ? 'edit' : $this->_subApp);
    }

    private function takeActionMeasures() : void {
        switch ($this->_routeParams['action']) {
            case 'edit':
                $this->requiredPermission = 'edit_articles';
                $this->_subApp = 'edit';
                $this->_model->setIsEditing(true);
                break;
            case 'delete':
                $this->requiredPermission = 'soft_delete_articles';
                $this->_subApp = 'delete';
                break;
            case 'print':
                $this->requiredPermission = 'print_articles';
                $this->hasSidebar = !self::$user->hasPermission('print_articles');
                $this->_subApp = 'print';
                break;
            case 'history':
                $this->requiredPermission = 'view_article_history';
                $this->_subApp = 'history';
                $this->_model->createHistoryTable();
                break;
            case 'assigntranslations':
                $this->requiredPermission = 'assign_translations';
                $this->_subApp = 'translations';
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
            $this->redirectTo(self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle());
        }
    }

    private function handleTranslationAssignmentRequest() : void {

    }

    private function delete() : void {
        if ($this->_model->getIsHomepage()) {
            $this->cantProceedException = 'cannot_delete_homepage';
            return;
        }

        if ($this->_model->softDelete())
            $this->redirectTo(self::$config->read('url') . '/index.php');
        else
            $this->cantProceedException = 'cannot_delete_article';
    }

    public function execute() : void {
        if (!empty($_POST) && $this->_subApp == 'edit')
            $this->handleEditRequest();
        else if (!empty($_POST) && $this->_subApp == 'translations')
            $this->handleTranslationAssignmentRequest();

        if ($this->ajax && !empty($this->parsedAJAXRequest['getlist'])) {
            $this->_model->setRequest('getlist');
            return;
        }

        $this->setTitle($this->_model->getTitle());

        if ($this->_subApp == 'delete') {
            $this->delete();
            return;
        }

        $this->_model->setSubApp($this->_subApp);

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet($this->_model->determineView(), $data, false);
    }

    public function display() : void {
        echo $this->_view;
    }

    public function getSidebarItems() : array {
        $sidebarLanguages = $this->_model->getSidebarLanguages();

        return [
            'article' => [
                'label' => 'current_article',
                'visible' => true,
                'items' => [
                    'view' => [
                        'label' => 'view_article',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle(),
                        'visible' => true
                    ],
                    'edit' => [
                        'label' => 'edit_article',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/edit',
                        'visible' => self::$user->hasPermission('edit_articles')
                    ],
                    'view_history' => [
                        'label' => 'view_history',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/history',
                        'visible' => self::$user->hasPermission('view_article_history')
                    ],
                    'assign_translations' => [
                        'label' => 'assign_translations',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/assigntranslations',
                        'visible' => self::$user->hasPermission('assign_translations')
                    ],
                    'print' => [
                        'label' => 'print_article',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/print',
                        'visible' => self::$user->hasPermission('print_articles')
                    ],
                    'delete' => [
                        'label' => 'delete_article',
                        'href' => self::$config->read('url') . '/index.php/article/' . $this->_model->getSafeTitle() . '/delete',
                        'visible' => self::$user->hasPermission('soft_delete_articles'),
                        'request_confirm' => true
                    ]
                ]
            ],
            'languages' => [
                'label' => 'languages',
                'visible' => !empty($sidebarLanguages),
                'items' => $sidebarLanguages
            ]
        ];
    }

    public function getAdditionalHeaders() : ?string {
        return $this->_subApp == 'print' && self::$user->hasPermission('print_articles') ? '
            <link href="https://fonts.googleapis.com/css?family=EB+Garamond" rel="stylesheet">
            <link rel="stylesheet" type="text/css" href="' . self::$config->read('url') . '/index.php/stylesheet/print" />' : null;
    }

    public function printJSON() : void {
        echo $this->_model->generateJSON();
    }
}