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

use DraiWiki\src\core\Connection;

abstract class Query {

    private $_connection, $_config, $_prefix;

    protected $query, $params;

    public function __construct($query) {
        $this->_connection = Registry::get('connection');
        $this->_config = Registry::get('config');

        $this->query = $query;
        $this->params = [];

        $this->_prefix = $this->_config->read('db_prefix');
        $this->connection = $this->_connection->getObject();
    }

    public function setParams($params) {
        $this->_params = empty($this->_params) ? $params : array_merge($this->_params, $params);
    }

    protected function setPrefix() {
        if (!empty($this->_prefix))
            $this->query = str_replace('{db_prefix}', $this->_prefix, $this->query);
    }

    public function insertLastId() {
        $this->_params['last_id'] = self::$_connection->getLastId();
    }

    public function toString() {
        return $this->_query;
    }
}
