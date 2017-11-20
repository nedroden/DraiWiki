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

namespace DraiWiki\src\tools\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;
use DraiWiki\src\tools\models\ArticleFinder as Model;

class ArticleFinder extends AppHeader {

    private $_model, $_view, $_errors;

    public function __construct() {
        $this->requiredPermission = 'find_article';
        $this->loadConfig();

        $this->checkForAjax();

        if ($this->ajax)
            $this->parseAjaxRequest();

        $this->_errors = [];
    }

    public function execute() : void {
        if ($this->ajax && !empty($this->parsedAJAXRequest['getresults'])) {
            $this->_model = new Model($_REQUEST['terms'] ?? null, $_REQUEST['start'] ?? 0, 15, !empty($this->parsedAJAXRequest['ignorelocales']));
            $this->_model->setRequest('getresults');
        }
        else
            $this->_model = new Model($_REQUEST['terms'] ?? null);

        if (($this->ajax && !empty($_REQUEST['terms'])) || !empty($_POST))
            $this->_model->validateInput($this->_errors);

        $this->setTitle($this->_model->getTitle());

        if (empty($this->_errors) && (!empty($_POST) || !empty($_REQUEST['terms']))) {
            $this->_model->parse($this->_errors);

            if (empty($this->_errors))
                $this->_model->loadResults();
        }

        $data = $this->_model->prepareData() + ['errors' => $this->_errors];
        $this->_view = Registry::get('gui')->parseAndGet('find', $data, false);
    }

    public function display() : void {
        echo $this->_view;
    }

    public function getAdditionalHeaders() : ?string {
        return '
            <script type="text/javascript" src="' . self::$config->read('url') . '/scripts/find.js"></script>';
    }

    public function printJSON() : void {
        echo $this->_model->generateJSON();
    }
}