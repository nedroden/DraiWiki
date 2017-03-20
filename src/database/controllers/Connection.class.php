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
 * This class creates a database connection
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\database\controllers;

use PDO;
use PDOException;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\controllers\Error;

class Connection {

	private $_connection, $_isConnected;
	private static $_instance;

	/**
	 * Establish a database connection and make sure PDO throws exceptions. That way the user won't (or at least, shouldn't) see any
	 * database errors. There are no parameters; the method uses the config array in the Main class to establish a connection.
	 * @return void
	 */
	private function __construct() {
		try {
			$this->_connection = new PDO('mysql:host=' . Main::$config->read('database', 'DB_SERVER') . ';
				dbname=' . Main::$config->read('database', 'DB_NAME') . ';
				charset=' . Main::$config->read('database', 'DB_CHARSET'),
				Main::$config->read('database', 'DB_USERNAME'), 
				Main::$config->read('database', 'DB_PASSWORD')
			);
		}
		catch (PDOException $e) {
			$error = new Error($e->getMessage(), [], true);
			$error->show();
		}

		try {
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			$error = new Error($e->getMessage(), [], true);
			$error->show();
		}
	}

	public static function instantiate() {
		if (self::$_instance == null)
			self::$_instance = new self();

		return self::$_instance;
	}

	public function executeQuery($query, $type, $params = []) {
		try {
			$pendingQuery = $this->_connection->prepare($query);
		}
		catch (PDOException $e) {
			$error = new Error(str_replace('{SQL_ERROR}', $e->getMessage(), 'The query could not be executed. This is what PDO returned:<br /> {SQL_ERROR}'), [$e->getMessage()], true);
			$error->show();
		}

		try {
			foreach ($params as $paramKey => $paramValue) {
				$pendingQuery->bindValue(':' . $paramKey, $paramValue);
			}

			$pendingQuery->execute();

			if ($type == 'select')
				return $pendingQuery->fetchAll();
			else
				return null;
		}
		catch (PDOException $e) {
			$error = new Error(str_replace('{SQL_ERROR}', $e->getMessage(), 'The query could not be executed. This is what PDO returned:<br /> {SQL_ERROR}'), [$e->getMessage()], true);
			$error->show();
		}
	}
}
