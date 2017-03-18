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

	private $_currentArticle, $_parsedown, $_isEditing, $_isHome;

	public function __construct($isHome) {
		$this->loadLocale();
		$this->loadUser();

		$this->_currentArticle = [];
		$this->_parsedown = new Parsedown();
		$this->_isHome = $isHome;
	}

	public function retrieve($title, $locale) {
		$query = new Query('
			SELECT a.ID, a.title, a.language, a.group_ID, a.status, h.article_ID, h.body, h.date_edited
				FROM {db_prefix}articles a
				INNER JOIN {db_prefix}history h ON (a.ID = h.article_ID)
				WHERE ' . ($this->_isHome ? 'a.ID' : 'a.title') . ' = :article
				AND a.language = :locale
				AND a.status = :status
				ORDER BY h.date_edited DESC
				LIMIT 1
		');

		$query->setParams([
			'article' => $this->addUnderscores($title),
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
				'action' => Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $this->sanitize($_GET['article']) . '&amp;edit',
				'language' => $locale,
				'group_ID' => 0
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
			$this->_currentArticle['language'] = $locale;
		}
		else {
			$this->_currentArticle['title'] = $this->ditchUnderscores($this->_currentArticle['title']);

			$this->_currentArticle['body_md'] = $this->_currentArticle['body'];
			$this->_currentArticle['body'] = $this->_parsedown->text($this->_currentArticle['body']);
		}

		return $this->_currentArticle;
	}

	public function getSubmenuItems() {
		$languages = $this->getSidebarLanguages();

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
					],
					'recent_changes' => [
						'label' => 'side_recent_changes',
						'href' => 'index.php?app=history&amp;id=' . $this->addUnderscores($this->_currentArticle['title']),
						'visible' => Permission::checkAndReturn('view_history'),
					]
				]
			],
			'language' => [
				'label' => 'other_languages',
				'visible' => true,
				'items' => $languages
			]
		];
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

	private function getSidebarLanguages() {
		$query = new Query('
			SELECT a.group_ID, a.language, l.code, l.native, a.title
				FROM {db_prefix}articles a
				INNER JOIN {db_prefix}locales l ON (a.language = l.code)
				WHERE a.language != :locale
				AND a.group_id = :group
				ORDER BY l.native ASC
		');

		$query->setParams([
			'locale' => $this->_currentArticle['language'],
			'group' => $this->_currentArticle['group_ID']
		]);

		$result = $query->execute();

		$languages = [];
		foreach ($result as $locale) {
			$languages[] = [
				'label' => $locale['native'],
				'href' => Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $locale['title'] . '&locale=' . $locale['code'],
				'visible' => true,
				'hardcoded' => true
			];
		}

		return $languages;
	}

	private function getLengths() {
		return [
			'title' => [
				'min' => Main::$config->read('article', 'MIN_TITLE_LENGTH'),
				'max' => Main::$config->read('article', 'MAX_TITLE_LENGTH')
			],
			'body' => [
				'min' => Main::$config->read('article', 'MIN_BODY_LENGTH'),
				'max' => 0
			]
		];
	}

	public function getHeader() {
		return '
		<link rel="stylesheet" type="text/css" href="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.css" />
		<script src="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.js"></script>';
	}

	public function verifyLength() {
		$data = $this->getLengths();

		$errors = [];
		foreach ($data as $field => $params) {
			$length = strlen($_POST[$field]);

			if ($length < $params['min'] && $params['min'] > 0)
				$errors[] = $field;
			else if ($length > $params['max'] && $params['max'] > 1)
				$errors[] = $field;
		}

		return $errors;
	}

	public function isValidId($id) {
		if (!is_numeric($id) || !is_int((int) $id))
			return false;
		else
			return true;
	}

	public function update() {
		$query = new Query('
			INSERT
				INTO {db_prefix}history (
					article_ID, body, date_edited, edited_by
				)
				VALUES (
					:id,
					:body,
					STR_TO_DATE(:date_edited, \'%m-%d-%Y %H:%i:%s\'),
					:edited_by
				)
		');

		$query->setParams([
			'id' => $_POST['articleID'],
			'body' => $_POST['body'],
			'date_edited' => date("m-d-Y H:i:s"),
			'edited_by' => $this->user->get()['ID']
		]);

		$query->execute('update');
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