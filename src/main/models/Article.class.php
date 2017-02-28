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

use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;
use \Parsedown;

class Article extends ModelController {

	private $_currentArticle, $_parsedown, $_title;

	public function __construct() {
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
			'title' => $title,
			'locale' => $locale,
			'status' => 1
		]);
		$result = $query->execute();

		if (count($result) == 0)
			return false;

		foreach ($result as $article) {
			foreach ($article as $key => $value) {
				$currentArticle[$key] = $value;
			}
		}

		$currentArticle['title'] = str_replace('_', ' ', $currentArticle['title']);
		$this->_title = $currentArticle['title'];

		$currentArticle['body_md'] = $currentArticle['body'];
		$currentArticle['body'] = $this->_parsedown->text($currentArticle['body']);

		return $currentArticle;
	}

	public function getTitle() {
		return $this->_title;
	}
}