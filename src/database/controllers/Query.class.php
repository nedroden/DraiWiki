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
		$this->_params = [];
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
		if (!is_array($fields))
			return null;

		$this->_query .= ' WHERE ';

		$queryFields = [];
		foreach ($fields as $key => $value) {
			$queryFields[] = ' ' . $key . ' = :VAL_PARAM_' . $key;
			$this->_params['VAL_PARAM_' . $key] = $value;
		}

		$this->_query .= implode('AND ', $queryFields);
		return $this;
	}

	public function orderBy($fields = []) {
		if (!is_array($fields))
			return null;

		$queryFields = [];
		foreach ($fields as $name => $direction) {
			$direction = strtoupper($direction);

			if (!$direction == 'ASC' || !$direction == 'DESC')
				return null;

			$queryFields[] = $name . ' ' . $direction;
		}

		$this->_query .= ' ORDER BY ' . implode('AND ', $queryFields);
		return $this;
	}

	public function execute() {
		$result = self::$_connection->executeQuery($this->_query, $this->_type, $this->_params);
		return $this->_type == 'select' ? $result : null;
	}

	public function toString() {
		return $this->_query;
	}
}