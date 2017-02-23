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
 * This class handles the login process
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\interfaces\App;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\auth\models\Login as Model;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/auth/models/Login.class.php';

class Login implements App {

	private $_view, $_template, $_model, $_hasStylesheet;

	public function __construct() {
		$this->_hasStylesheet = true;

		$this->_view = new View('Login');
		$this->_model = new Model();
		$this->_template = $this->_view->get();

		if (!empty($_POST))
			$this->handle();

		$this->setTemplateData();
	}

	public function show() {
		$this->_template->showContent();
	}

	public function getStylesheets() {
		return ['registration'];
	}

	public function getTitle() {
		return $this->_model->getTitle();
	}

	public function getHasStylesheet() {
		return $this->_hasStylesheet;
	}

	private function setTemplateData() {
		$this->_template->setData([
			'errors' => [],
			'action' => $this->_model->getAction(),
		]);
	}

	private function handle() {
		$result = $this->_model->validate();

		if (!is_array($result)) {
			$this->setSession($result);
			$this->redirectToIndex();
		}
		else
			$this->setTemplateData($result['errors'], $result['correct']);
	}

	private function setSession($ID) {
		$_SESSION['user'] = $this->getSessionInfo($ID, 1);
		$sessionID = session_id();
		setcookie(Main::$config->read('session', 'COOKIE_ID'), $sessionID, 60 * 60 * 24 * 7 * 52);
	}

	private function getSessionInfo($userID, $lifetime) {
		return [
			'ID' => $userID,
			'UA' => $_SERVER['HTTP_USER_AGENT'],
			'IP' => $_SERVER['REMOTE_ADDR'],
			'LT' => $lifetime
		];
	}

	private function redirectToIndex() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL'));
		exit();
	}
}