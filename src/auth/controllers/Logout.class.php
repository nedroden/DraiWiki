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
 * This class handles the logout process
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\User;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\controllers\NoAccessError;

class Logout {

	private $_cookieName;

	public function __construct() {
		$this->_user = User::instantiate();

		$this->_cookieName = Main::$config->read('session', 'COOKIE_NAME');
		$this->handle();
	}

	public function handle() {
		if ($this->_user->isGuest())
			$this->redirect();

		session_destroy();
		if (!empty($_COOKIE[$this->_cookieName]))
			unset($_COOKIE[$this->_cookieName]);
		$this->redirect();
	}

	public function redirect() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL'));
		die();
	}
}