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
 * This class makes sure the user is able to register a new account.
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
use DraiWiki\src\auth\models\Agreement;
use DraiWiki\src\auth\models\Registration as Model;
use DraiWiki\src\main\models\Locale;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/auth/models/Registration.class.php';
require_once Main::$config->read('path', 'BASE_PATH') . 'src/auth/models/Agreement.class.php';

class Registration implements App {

	private $_model, $_view, $_template, $_hasStylesheet, $_agreement, $_errors, $_correctFields;

	public function __construct() {
		$this->_hasStylesheet = true;

		$this->_view = new View('Registration');
		$this->_model = new Model();
		$this->_template = $this->_view->get();

		$this->_errors = [];

		if (!empty($_POST))
			$this->handle();

		$this->getAgreement();
		$this->setTemplateData();
	}

	public function show() {
		$this->_template->showContent();
	}

	private function getAgreement() {
		$agreement = new Agreement();
		$this->_agreement = $agreement->retrieve();
	}

	public function getTitle() {
		return $this->_model->getTitle();
	}

	public function getStylesheets() {
		return ['registration'];
	}

	public function getHasStylesheet() {
		return $this->_hasStylesheet;
	}

	private function setTemplateData() {
		$this->_template->setData([
			'action' => $this->_model->getAction(),
			'agreement' => $this->_agreement,
			'errors' => $this->_errors,
			'correct' => $this->_correctFields
		]);
	}

	private function handle() {
		$result = $this->_model->verify();

		if (empty($result)) {
			$this->_model->addToDatabase();
			$this->redirectToLogin();
		}
		else {
			$this->_errors = $result['errors'];
			$this->_correctFields = $result['correct'];
		}
	}

	private function redirectToLogin() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL') . '?app=login');
		die('Redirect disabled.');
	}
}