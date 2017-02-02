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

namespace DraiWiki\src\main\controllers;

use PDO;
use PDOException;
use DraiWiki\src\database\controllers\Main;

abstract class Database {

	private $_connection;

	/**
	 * Establish a database connection and make sure PDO throws exceptions. That way the user won't (or at least, shouldn't) see any
	 * database errors. There are no parameters, the method uses the config array in the Main class to establish a connection.
	 * @return void
	 */
	protected function connect() {
		try {
			$this->_connection = new PDO('
				mysql:host=' . $host . ';
				dbname=' . Main::$config->read('database', 'DB_NAME') . ';
				charset=' . Main::$config->read('database', 'DB_CHARSET'),
				Main::$config->read('database', 'DB_USERNAME'), 
				Main::$config->read('database', 'DB_PASSWORD')
			);
		}
		catch (PDOException $e) {
			die('Database connection failed.');
		}

		try {
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			die('Unable to set PDO error mode. Aborting for security reasons.');
		}
	}

	/**
	 * Returns the database connection
	 * @return PDO $_connection
	 */
	protected function getConnection() {
		return $this->_connection;
	}
}