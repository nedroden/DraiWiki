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
 * This class is used for editing articles.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\controllers\Permission;
use DraiWiki\src\main\controllers\App;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\Editor as Model;
use DraiWiki\src\main\models\Locale;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Editor.class.php';

class Editor extends App {

	private $_model, $_template;

	public function __construct() {
		$this->hasStylesheet = false;

		$this->redirectIfEmpty();

		$this->_model = new Model();

		if (!empty($_POST))
			$this->handlePostRequest();

		$view = new View('Editor');
		$this->_template = $view->get();

		$this->_template->setData(
			array_merge($this->_model->get(), ['action' => 'test'])
		);
	}

	public function show() {
		$this->_template->showContent();
	}

	private function redirectIfEmpty() {
		if (empty($_GET['id'])) {
			header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php');
			die;
		}
	}

	private function handlePostRequest() {
		echo 'Hello, I\'m awesome.';
	}

	public function getHeader() {
		return $this->_model->getHeader();
	}

	public function getTitle() {
		return $this->_model->getTitle();
	}
}
