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

use PDO;
use PDOException;
use DraiWiki\src\main\controllers\Main;

class Query extends Connection {

	private $_type, $_query, $_params;

	private static $_connection;

	public function __construct() {
		if (self::$_connection == null)
			self::$_connection = parent::instantiate();

		$this->_query = '';
	}

	public function retrieve(...$fields) {
		$this->_type = 'select';
		$this->_query .= 'SELECT ' . implode(', ', $fields);
		return $this;
	}

	public function retrieveAll() {
		$this->_type = 'select';
		$this->_query .= 'SELECT *';
		return $this;
	}

	public function from(...$tables) {
		if (!$this->_type == 'select')
			return null;
		else {
			$this->_query .= ' FROM ' . implode(', ', $tables);
			return $this;
		}
	}

	public function where($fields = []) {
		$this->_query .= ' ';

		if (!is_array($fields))
			return null;

		$queryFields = '';
		foreach ($fields as $key => $value) {
			$queryFields .= ' ' . $value . ' = :VAL_' . $value;
			$this->_params[$value] = $shouldBe[$key];
		}

		$this->_query .= implode('AND ', $queryFields);
		return $this;
	}

	public function toString() {
		return $this->_query;
	}
}