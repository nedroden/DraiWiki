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
use DraiWiki\src\database\controllers\Connection;
use DraiWiki\src\main\controllers\Main;

class Query {

	private $_type, $_query, $_params, $_prefix;

	private static $_connection;

	public function __construct() {
		if (self::$_connection == null)
			self::$_connection = Connection::instantiate();

		$this->_query = '';
		$this->_params = [];
		$this->_prefix = Main::$config->read('database', 'DB_PREFIX');
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
			foreach ($tables as $key => $table) {
				$tables[$key] = $this->_prefix . $table;
			}

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
			$queryFields[] = $key . ' = :' . $key;
			$this->_params[$key] = $value;
		}

		$this->_query .= implode(' AND ', $queryFields);
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

	public function limit($amount) {
		$this->_query .= ' LIMIT ' . $amount;
		return $this;
	}

	public function execute() {
		$result = self::$_connection->executeQuery($this->_query . ';', $this->_type, $this->_params);
		return $this->_type == 'select' ? $result : null;
	}

	public function toString() {
		return $this->_query;
	}
}