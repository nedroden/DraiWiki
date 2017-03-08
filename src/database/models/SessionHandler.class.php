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
 * This class adds a session to the database
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\database\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\Connection;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;
use \SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface {

	public function __construct() {
		$this->_database = Connection::instantiate();

		session_set_save_handler(
			[$this, 'open'],
			[$this, 'close'],
			[$this, 'read'],
			[$this, 'write'],
			[$this, 'destroy'],
			[$this, 'gc']
		);
		register_shutdown_function('session_write_close');
		session_name(Main::$config->read('session', 'COOKIE_ID'));
	}

	/**
	 * Servers running Ubuntu or its deratives (this may apply to other Debian-based systems as well) do
	 * not automatically run the garbage collector. Thus, we need to call it ourselves.
	 */
	public function __destruct() {
		$probability = rand(0, 2);
		if ($probability == 0)
			$this->gc(init_get('session.gc_max_lifetime'));
	}

	public function open($savePath, $sessionKey) {
		/*$query = new Query('
			SELECT data
				FROM {db_prefix}sessions
				WHERE session_key = :session_key
				LIMIT 1
		');

		$query->setParams([
			'session_key' => $sessionKey
		]);
		$result = $query->execute();

		foreach ($result as $resultFound) {
			return true;
		}

		return false;*/
		return true;
	}

	public function close() {
		return true;
	}

	public function read($session_key) {
		$query = new Query('
			SELECT `data`
				FROM {db_prefix}sessions
				WHERE session_key = :session_key
				LIMIT 1
		');

		$query->setParams([
			'session_key' => $session_key
		]);

		$result = $query->execute();

		foreach ($result as $session)
			return $session['data'];

		return '';
	}

	public function write($session_key, $data) {
		$query = new Query('
			REPLACE
				INTO {db_prefix}sessions
				VALUES (
					:session_key,
					:data,
					:created_at
				)
		');

		$query->setParams([
			'session_key' => $session_key,
			'data' => $data,
			'created_at' => time()
		]);

		$query->execute('update');
		return true;
	}

	public function destroy($session_key) {
		$query = new Query('
			DELETE
				FROM {db_prefix}sessions
				WHERE session_key = :session_key
		');

		$query->setParams(['session_key' => $session_key]);

		$query->execute('update');
		return true;
	}

	public function gc($lifetime) {
		$obsolete = time() - $lifetime;

		$query = new Query('
			DELETE
				FROM {db_prefix}sessions
				WHERE created_at < :obsolete
		');

		$query->setParams(['obsolete' => $obsolete]);
		$query->execute('update');

		return true;
	}
}