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

namespace DraiWiki\src\core\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\GUI;

abstract class Query {

    private $_connection, $_config, $_prefix;

    protected $query, $params, $hasTemplate;

    public function __construct(string $query) {
        $this->_connection = Registry::get('connection');
        $this->_config = Registry::get('config');

        $this->query = $query;
        $this->params = [];

        $this->_prefix = $this->_config->read('db_prefix');
        $this->connection = $this->_connection->getObject();
        $this->setPrefix();

        // If we're using an emulated entry point, we don't have (nor do we need) a template
        if (!defined('EntryPointEmulation') && !defined('ForceTemplateLookup'))
            $this->canUseTemplate();
        else
            $this->hasTemplate = false;
    }

    private function canUseTemplate() : void {
        $this->hasTemplate = GUI::$gui_loaded;
    }

    public function setParams(array $params) : void {
        $this->params = empty($this->_params) ? $params : array_merge($this->_params, $params);
    }

    protected function setPrefix() : void {
        if (!empty($this->_prefix))
            $this->query = str_replace('{db_prefix}', $this->_prefix, $this->query);
    }

    public function insertLastId() : void {
        $this->params['last_id'] = $this->_connection->getLastId();
    }

    public function __toString() : string {
        return $this->query;
    }
}
