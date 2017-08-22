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

use DraiWiki\src\main\models\DebugBarWrapper;
use PDO;
use PDOException;

class Connection {

    private $_config, $_connection;

    public function __construct() {
        $this->_config = Registry::get('config');
        $this->connect();
        $this->destroyLoginData();
    }

    public function getObject() : PDO {
    	return $this->_connection;
    }

    private function connect() : void {
		$this->_config = Registry::get('config');

		try {
			$this->_connection = new PDO('mysql:host=' . $this->_config->read('db_server') . ';
				dbname=' . $this->_config->read('db_name') . ';
				charset=' . $this->_config->read('db_charset'),
				$this->_config->read('db_username'),
				$this->_config->read('db_password')
			);
		}
		catch (PDOException $e) {
            die('Could not establish a database connection.');
		}

		try {
			$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			die('Could not set PDO error mode.');
		}

        if (!defined('EntryPointEmulation'))
            DebugBarWrapper::report('Database connection established');
    }

    private function destroyLoginData() : void {
        $this->_config->deleteDatabaseInfo();
    }
}
