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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\auth\controllers\Permission;
use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;
use \Parsedown;

class Article extends ModelController {

	private $_currentArticle, $_parsedown, $_isEditing;

	public function __construct() {
		$this->loadLocale();
		$this->_currentArticle = [];
		$this->_parsedown = new Parsedown();
	}

	public function retrieve($title, $locale) {
		$query = new Query('
			SELECT a.ID, a.title, a.language, a.group_ID, a.status, h.article_ID, h.body, h.date_edited
				FROM {db_prefix}articles a
				INNER JOIN {db_prefix}history h ON (a.ID = h.article_ID)
				WHERE a.title = :title
				AND a.language = :locale
				AND a.status = :status
				ORDER BY h.date_edited DESC
				LIMIT 1
		');

		$query->setParams([
			'title' => $this->addUnderscores($title),
			'locale' => $locale,
			'status' => 1
		]);
		$result = $query->execute();

		foreach ($result as $article) {
			foreach ($article as $key => $value) {
				$this->_currentArticle[$key] = $value;
			}
		}

		if (count($result) == 0) {
			$this->_isEditing = true;
			$this->_currentArticle = [
				'title' => $this->ditchUnderscores($this->sanitize($_GET['article'])),
				'body' => '',
				'action' => Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $this->sanitize($_GET['article']) . '&amp;edit'
			];
		}
		else if (isset($_GET['edit'])) {
			$this->_isEditing = true;

			if (empty($_GET['article']))
				$article = $this->locale->getLanguage()['homepage'];
			else
				$article = $_GET['article'];

			// We have already loaded the page, we just need to remove the underscores from the title
			$this->_currentArticle['title'] = $this->ditchUnderscores($this->_currentArticle['title']);
			$this->_currentArticle['action'] = Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $this->sanitize($article) . '&amp;edit';
		}
		else {
			$this->_currentArticle['title'] = $this->ditchUnderscores($this->_currentArticle['title']);

			$this->_currentArticle['body_md'] = $this->_currentArticle['body'];
			$this->_currentArticle['body'] = $this->_parsedown->text($this->_currentArticle['body']);
		}

		return $this->_currentArticle;
	}

	public function getSubmenuItems() {
		return [
			'article' => [
				'label' => 'article_actions',
				'visible' => true,
				'items' => [
					'view' => [
						'label' => 'side_read_article',
						'href' => 'index.php?article=' . $this->addUnderscores($this->_currentArticle['title']),
						'visible' => true,
					],
					'edit' => [
						'label' => 'side_edit_article',
						'href' => 'index.php?article=' . $this->addUnderscores($this->_currentArticle['title']) . '&amp;edit',
						'visible' => Permission::checkAndReturn('edit_articles'),
					]
				]
			]
		];
	}

	public function getHeader() {
		return '

		<link rel="stylsheet" type="text/css" href="' . Main::$config->read('path', 'BASE_URL'). 'libraries/SimpleMDE/css/simplemde.css" />
		<script type="text/javascript" src="' . Main::$config->read('path', 'BASE_URL'). 'libraries/SimpleMDE/js/simplemde.js"></script>';
	}

	private function sanitize($value) {
		return htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
	}

	private function addUnderscores($text) {
		return str_replace(' ', '_', $text);
	}

	private function ditchUnderscores($text) {
		return str_replace('_', ' ', $text);
	}

	public function getIsEditing() {
		return $this->_isEditing;
	}

	public function getTitle() {
		return ($this->_isEditing ? $this->locale->read('editor', 'edit_article_title') : $this->_currentArticle['title']);
	}

	public function setIsEditing($value) {
		$this->_isEditing = $value;
	}
}