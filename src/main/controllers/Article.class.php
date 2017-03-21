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
 * This class is used for loading articles.
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
use DraiWiki\src\main\models\Article as Model;
use DraiWiki\src\main\models\Locale;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Article.class.php';

class Article extends App {

	private $_model, $_article;

	public function __construct($currentPage) {
		$this->hasStylesheet = true;
		$this->_locale = Locale::instantiate();

		$this->_model = new Model($currentPage);
		$article = $this->_model->get();

		if (!empty($_POST))
			$this->handlePostRequest();

		$view = new View('Article');
		$this->_template = $view->get();
		$this->_template->setData($article);
	}

	public function show() {
		$this->_template->showContent();
	}

	private function redirect() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $this->_currentPage);
		die;
	}

	private function redirectIfEmpty() {
		if (empty($_GET['id'])) {
			header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php');
			die;
		}
	}

	private function handlePostRequest() {
		$errors = $this->_model->validate();
		$this->_errors = !empty($errors) ? $errors : [];

		if (empty($errors))
			$this->redirect();
	}

	public function getTitle() {
		return $this->_model->getTitle();
	}

	public function getHeader() {
		return $this->_model->getHeader();
	}

	public function getStylesheets() {
		return ['article'];
	}

	public function getSubmenuItems() {
		return $this->_model->getSubmenuItems();
	}
}
