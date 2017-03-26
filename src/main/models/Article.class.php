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

	private $_info, $_isHomepage, $_title, $_isEditing, $_params;

	public function __construct($article, $params = [], $forceEdit = false) {
		$this->_isHomepage = empty($article);
		$this->_isEditing = $forceEdit;
		$this->_params = $params;

		$this->loadLocale();
		$this->loadUser();

		$this->_title = !empty($article) ? $article : $this->locale->getLanguage()['homepage'];

		$this->_parsedown = new Parsedown();

		$this->load();
	}

	public function get() {
		return $this->_info;
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
						'href' => Main::$config->read('path', 'BASE_URL') . 'index.php/article/' . $this->addUnderscores($this->_info['title']),
						'visible' => true,
					],
					'edit' => [
						'label' => 'side_edit_article',
						'href' => Main::$config->read('path', 'BASE_URL') . 'index.php/article/' . $this->addUnderscores($this->_info['title']) . '/edit',
						'visible' => Permission::checkAndReturn('edit_articles'),
					],
					'recent_changes' => [
						'label' => 'side_recent_changes',
						'href' => Main::$config->read('path', 'BASE_URL') . 'index.php/timeline/' . $this->addUnderscores($this->_info['title']),
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

	private function load() {
		$query = new Query('
			SELECT a.ID, a.title, a.language, a.group_ID, a.status, h.article_ID, h.body, h.date_edited
				FROM {db_prefix}articles a
				INNER JOIN {db_prefix}history h ON (a.ID = h.article_ID)
				WHERE ' . ($this->_isHomepage ? 'a.ID' : 'a.title') . ' = :article
				AND a.language = :locale
				AND a.status = :status
				ORDER BY h.date_edited DESC
				LIMIT 1
		');

		$query->setParams([
			'article' => $this->addUnderscores($this->_title),
			'locale' => $this->locale->getLanguage()['code'],
			'status' => 1
		]);

		$result = $query->execute();

		foreach ($result as $article) {
			foreach ($article as $key => $value) {
				$this->_info[$key] = $value;
			}

			$found = true;
		}

		if (empty($found) || $this->_isEditing) {
			$this->_isEditing = true;
			$this->locale->loadFile('editor');
			$this->_info['action'] = Main::$config->read('path', 'BASE_URL');
		}

		if (!empty($found)) {
			$this->_info['title'] = $this->ditchUnderscores($this->_info['title']);
			$this->_info['body_md'] = $this->_info['body'];

			if ($this->_isEditing)
				$this->_info['body'] = $this->sanitize($this->_info['body']);
			else
				$this->_info['body'] = $this->_parsedown->setMarkupEscaped(true)->text($this->_info['body']);
		}

		// Even if we haven't loaded an article, the editor page still needs some basic information
		else {
			$this->_info = [
				'id' => 0,
				'title' => $this->locale->read('editor', 'new_article'),
				'body_md' => '',
				'body' => '',
				'language' => $this->locale->getLanguage()['code'],
				'group_ID' => 0
			];
		}
	}

	public function validate() {
		$errors = $this->getEmptyFields();

		if (empty($errors)) {
			return array_merge($errors, $this->getFieldsWithInvalidLength());
		}
		else
			return $errors;
	}

	private function getEmptyFields() {
		$requiredFields = ['id', 'title', 'body'];

		$errors = [];
		foreach ($requiredFields as $field) {
			if (empty($_POST[$field]))
				$errors[$field] = 'empty_' . $field;
		}

		return $errors;
	}

	private function getFieldLengths() {
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

	private function getFieldsWithInvalidLength() {
		$rules = $this->getFieldLengths();
		$errors = [];

		foreach ($rules as $key => $value) {
			if (strlen($_POST[$key]) < $value['min'] && $value['min'] != 0)
				$errors[$key] = 'too_short_' . $key;
			else if (strlen($_POST[$key]) > $value['max'] && $value['max'] != 0)
				$errors[$key] = 'too_long_' . $key;
		}

		return $errors;
	}

	private function isCorrectId() {

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
			'locale' => $this->_info['language'],
			'group' => $this->_info['group_ID']
		]);

		$result = $query->execute();

		$languages = [];
		foreach ($result as $locale) {
			$languages[] = [
				'label' => $locale['native'],
				'href' => Main::$config->read('path', 'BASE_URL') . 'index.php/' . $locale['code'] . '/' . $locale['title'],
				'visible' => true,
				'hardcoded' => true
			];
		}

		return $languages;
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
			'id' => $_POST['id'],
			'body' => $_POST['body'],
			'date_edited' => date("m-d-Y H:i:s"),
			'edited_by' => $this->user->get()['ID']
		]);

		$query->execute('update');
	}

	public function getHeader() {
		return '
		<link rel="stylesheet" type="text/css" href="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.css" />
		<script src="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.js"></script>';
	}

	public function getTitle() {
		return !$this->_isEditing ? $this->_info['title'] : $this->locale->read('editor', 'edit_article') . $this->_info['title'];
	}

	public function getIsEditing() {
		return $this->_isEditing;
	}
}
