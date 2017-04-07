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

	public function __construct($currentPage, $forceEdit = false) {
		$this->hasStylesheet = true;

		$this->_model = new Model($currentPage, $this->params, $forceEdit);
		$article = $this->_model->get();

		if (!empty($_POST))
			$this->handlePostRequest();

		$viewName = $this->_model->getIsEditing() ? 'editor' : 'article';
		$view = new View($viewName);
		$this->_template = $view->get();

		$this->_template->setData($article);
	}

	public function show() {
		if ($this->_model->getIsEditing() && !Permission::checkAndReturn('edit_articles')) {
			NoAccessError::show();
			return;
		}

		$this->_template->showContent();
	}

	private function redirect() {
		header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php/article/' . $this->_model->addUnderscores($_POST['title']));
		die;
	}

	private function redirectIfEmpty() {
		if (empty($_GET['id'])) {
			header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php');
			die;
		}
	}

	private function handlePostRequest() {
		if (!Permission::checkAndReturn('edit_articles'))
			return;

		$errors = $this->_model->validate();
		$this->_errors = !empty($errors) ? $errors : [];

		if (empty($this->_errors) && !$this->_model->getIsNew() && $this->_model->getHasChangedTitle()) {
			if (!$this->_model->isUsedTitle($_POST['title']))
				$this->_model->updateTitle();
			else
				$this->_errors = array_merge($this->_errors, ['title' => 'title_in_use']);
		}

		if (empty($this->_errors)) {
			if ($this->_model->getIsNew() && !$this->_model->isUsedTitle($_POST['title']))
				$this->_model->create();
			else if ($this->_model->getIsNew() && $this->_model->isUsedTitle($_POST['title'])) {
				$this->_errors = array_merge($this->_errors, ['title' => 'title_in_use']);
				return;
			}
			else
				$this->_model->update();

			$this->redirect();
		}
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
