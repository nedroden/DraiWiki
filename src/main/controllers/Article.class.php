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
 * This class is used for loading pages.
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

	private $_view, $_template, $_isHome, $_currentPage, $_locale, $_errors;

	public function __construct($isHome, $currentPage = null) {
		$this->hasStylesheet = true;
		$this->_locale = Locale::instantiate();

		$this->_isHome = $isHome;
		$this->_currentPage = ($currentPage == null || $this->_isHome) ? $this->_locale->getLanguage()['homepage'] : $currentPage;

		$this->_model = new Model($isHome);
		$article = $this->_model->retrieve($this->_currentPage, $this->_locale->getLanguage()['code']);

		if (!empty($_POST))
			$this->handlePostRequest();

		$this->_view = new View($this->_model->getIsEditing() ? 'Editor' : 'Article');
		$this->_template = $this->_view->get();

		$this->_template->setData($article);
	}

	public function show() {
		if ($this->_model->getIsEditing() && !Permission::checkAndReturn('edit_articles')) {
			Permission::yell();
			return;
		}

		$this->_template->showContent();
	}

	private function handlePostRequest() {
		if (!Permission::checkAndReturn('edit_articles')) {
			Permission::yell();
			return;
		}

		$this->_errors = [];

		if (!empty($fields = $this->getEmptyFields())) {
			foreach ($fields as $field) {
				$this->_errors[$field] = 'empty_' . $field;
			}
			return;
		}

		if (!empty($fields = $this->_model->verifyLength())) {
			foreach ($fields as $field) {
				// @todo: add error messages to locale file
				$this->_errors[$field] = 'invalid_length_' . $field;
			}
			return;
		}

		if (!$this->_model->isValidId($_POST['articleID'])) {
			$this->_errors['articleID'] = 'invalid_ID';
			return;
		}

		$this->_model->update();
		$this->redirect();
	}

	private function getEmptyFields() {
		$fields = ['articleID', 'title', 'body'];

		$errors = [];
		foreach ($fields as $field)
			if (empty($_POST[$field]))
				$errors[] = $field;

		return $errors;
	}

	private function redirect() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $this->_currentPage);
		die;
	}

	public function getHeader() {
		return $this->_model->getHeader();
	}

	public function getTitle() {
		return $this->_model->getTitle();
	}

	public function getStylesheets() {
		return ['article'];
	}

	public function getSubmenuItems() {
		return $this->_model->getSubmenuItems();
	}
}