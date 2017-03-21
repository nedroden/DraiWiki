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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\auth\controllers\Permission;
use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;

class Editor extends ModelController {

	private $_info;

	public function __construct() {
		$this->loadLocale();
		$this->load();

		$this->locale->loadFile('editor');
	}

	public function get() {
		return $this->_info;
	}

	private function load() {
		$query = new Query('
			SELECT a.ID, a.title, a.language, a.group_ID, a.status, h.article_ID, h.body, h.date_edited
				FROM {db_prefix}articles a
				INNER JOIN {db_prefix}history h ON (a.ID = h.article_ID)
				WHERE a.title = :article
				AND a.language = :locale
				AND a.status = :status
				ORDER BY h.date_edited DESC
				LIMIT 1
		');

		$query->setParams([
			'article' => $this->addUnderscores($_GET['id']),
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
	}

	public function validate() {

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

	private function addUnderscores($text) {
		return str_replace(' ', '_', $text);
	}

	private function ditchUnderscores($text) {
		return str_replace('_', ' ', $text);
	}

	public function getHeader() {
		return '
		<link rel="stylesheet" type="text/css" href="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.css" />
		<script src="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.js"></script>';
	}

	public function getTitle() {
		return $this->locale->read('editor', 'edit_article') . $this->_info['title'];
	}
}
