<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 *
 * Class information:
 * This class is used to connect the Connection and Query classes.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\database\controllers;

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\database\controllers\Connection;
use DraiWiki\src\main\controllers\Main;

class Query {

	private $_type, $_query, $_params, $_prefix;

	private static $_connection;

	public function __construct($query) {
		if (self::$_connection == null)
			self::$_connection = Registry::get('connection');

		$this->_query = $query;
		$this->_params = [];
		$this->_prefix = Main::$config->read('database', 'DB_PREFIX');
	}

	public function setParams($params) {
		$this->_params = $params;
	}

	public function execute($type = 'select') {
		if (!empty($this->_prefix))
			$this->_query = str_replace('{db_prefix}', $this->_prefix, $this->_query);

		return self::$_connection->executeQuery($this->_query . ';', $type, $this->_params);
	}

	public function toString() {
		return $this->_query;
	}
}
