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

	private $_connection;
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
			die('<h1>Database connection failed</h1>Aborting for security reasons.');
		}

		try {
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			die('<h1>Unable to set PDO error mode.</h1>Aborting for security reasons.');
		}
	}

	public static function instantiate() {
		if (self::$_instance == null)
			self::$_instance = new self();

		return self::$_instance;
	}

	protected function executeQuery($query, $type, $params = []) {
		try {
			$pendingQuery = $this->_connection->prepare($query);
		}
		catch (PDOException $e) {
			die('<h1>Unable to execute query.</h1>Please try again. Aborting for security reasons.');
		}

		try {
			foreach ($params as $paramKey => $paramValue)
				$pendingQuery->bindParam(':' . $paramKey, $paramValue);

			$pendingQuery->execute();
			$result = $pendingQuery->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e) {
			$error = new Error('execute_query_failure', [$e->getMessage()]);
			$error->show();
		}
		finally {
			return $type == 'select' ? $result : null;
		}
	}
}