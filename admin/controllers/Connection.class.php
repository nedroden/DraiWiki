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

namespace DraiWiki\admin\controllers;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use PDO;
use PDOException;

/**
 * This class contains the required methods to establish a database connection.
 *
 * @since		1.0 Alpha 1
 */
class Connection {

	private $_config, $_connection, $_isConnected;

	/**
	 * Establish a database connection and make sure PDO throws exceptions. That way the user won't (or at least, shouldn't) see any
	 * database errors. There are no parameters; the method uses the config array in the Main class to establish a connection.
	 * @return void
	 */
	public function __construct() {
		$this->_config = Registry::get('conf_admin');

		try {
			$this->_connection = new PDO('mysql:host=' . $this->_config->read('database', 'DB_SERVER') . ';
				dbname=' . $this->_config->read('database', 'DB_NAME') . ';
				charset=' . $this->_config->read('database', 'DB_CHARSET'),
				$this->_config->read('database', 'DB_USERNAME'),
				$this->_config->read('database', 'DB_PASSWORD')
			);
		}
		catch (PDOException $e) {
			$error = new Error('Could not establish a database connection. This is what PDO returned:<br /> {SQL_ERROR}', ['SQL_ERROR' => $e->getMessage()], true);
			$error->show();
		}

		try {
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			$error = new Error('Could not establish a database connection. This is what PDO returned:<br /> {SQL_ERROR}', ['SQL_ERROR' => $e->getMessage()], true);
			$error->show();
		}
	}

	public function executeQuery($query, $type, $params = []) {
		try {
			$pendingQuery = $this->_connection->prepare($query);
		}
		catch (PDOException $e) {
			$error = new Error('The query could not be executed. This is what PDO returned:<br /> {SQL_ERROR}', ['SQL_ERROR' => $e->getMessage()], true);
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
			$error = new Error('The query could not be executed. This is what PDO returned:<br /> {SQL_ERROR}', ['SQL_ERROR' => $e->getMessage()], true);
			$error->show();
		}
	}
}
