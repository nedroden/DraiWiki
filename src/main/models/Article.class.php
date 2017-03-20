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

	private $_info, $_isHomepage, $_title, $_isNew;

	public function __construct($article) {
		$this->_isHomepage = empty($article);
		
		if (!empty($article))
			$this->_title = $article;
		else
			$this->_title = Main::$config->read('wiki', 'WIKI_HOMEPAGE');

		$this->loadLocale();
		$this->loadUser();

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
						'href' => 'index.php?article=' . $this->addUnderscores($this->_info['title']),
						'visible' => true,
					],
					'edit' => [
						'label' => 'side_edit_article',
						'href' => 'index.php?app=edit&amp;id=' . $this->addUnderscores($this->_info['title']),
						'visible' => Permission::checkAndReturn('edit_articles'),
					],
					'recent_changes' => [
						'label' => 'side_recent_changes',
						'href' => 'index.php?app=history&amp;id=' . $this->addUnderscores($this->_info['title']),
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
		
		// Don't redirect if the homepage is missing (to prevent an infinite loop)
		if (empty($found) && !$this->_isHomepage) {
			header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php?app=edit&id=' . $this->_title);
			die;
		}

		$this->_info['title'] = $this->ditchUnderscores($this->_info['title']);
		$this->_info['body_md'] = $this->_info['body'];
		$this->_info['body'] = $this->_parsedown->text($this->_info['body']);
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
				'href' => Main::$config->read('path', 'BASE_URL') . 'index.php?article=' . $locale['title'] . '&amp;locale=' . $locale['code'],
				'visible' => true,
				'hardcoded' => true
			];
		}

		return $languages;
	}

	public function getHeader() {
		return '
		<link rel="stylesheet" type="text/css" href="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.css" />
		<script src="' . Main::$config->read('path', 'BASE_URL') . 'node_modules/simplemde/dist/simplemde.min.js"></script>';
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

	public function getTitle() {
		return $this->_info['title'];
	}
}
