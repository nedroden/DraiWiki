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

use DraiWiki\src\admin\models\LocaleManagement as Model;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;

class LocaleManagement extends AppHeader {

    private $_model, $_view, $_subAction, $_localeCode, $_errors;

    public function __construct() {
        $this->_model = new Model();
        $this->_errors = [];

        $route = Registry::get('route')->getParams();
        $this->_model->createInstalledLocalesTable();

        if (!empty($route['action'])) {
            switch ($route['action']) {
                case 'add':
                case 'setasdefault':
                case 'delete':
                    if (empty($route['id']))
                        $this->cantProceedException = 'no_id_specified';

                    break;
                default:
                    $this->cantProceedException = 'unknown_action';
            }

            $this->_subAction = $route['action'];
            $this->_localeCode = $route['id'] ?? 0xFF;
        }
    }

    public function getTitle() : string {
        return $this->_model->getTitle();
    }

    public function getPageDescription() : string {
        return $this->_model->getPageDescription();
    }

    public function execute() : void {
        if (!empty($this->_subAction)) {
            switch ($this->_subAction) {
                case 'add':
                    $error = $this->_model->installLocale($this->_localeCode);
                    break;
                case 'delete':
                    $error = $this->_model->deleteLocale($this->_localeCode);
                    break;
                case 'setasdefault':
                    $error = $this->_model->setDefaultLocale($this->_localeCode);
                    break;
                default:
                    $error = 'unknown_action';
            }

            // @todo Move to the model
            if (!empty($error))
                $this->_errors[] = $error;
            else
                $this->redirectTo(self::$config->read('url') . '/index.php/management/locales');
        }

        $this->_model->handleMissingLocales($this->_errors);

        $this->_view = Registry::get('gui')->parseAndGet('admin_locales', array_merge($this->_model->prepareData(), ['errors' => $this->_errors]), false);
    }

    public function display() : void {
        echo $this->_view;
    }
}