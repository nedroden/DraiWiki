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

class Article extends ModelController {

	private $_currentArticle;

	public function __construct() {
		$this->_currentArticle = [];
	}

	public function retrieve($title, $locale) {
		$query = new Query();
		$result = $query->retrieve('ID', 'title', 'language', 'group_ID', 'status')
						->from('articles')
						->where([
							'title' => $title,
							'language' => $locale
						])
						->limit(1)
						->execute();

		foreach ($result as $article) {
			foreach ($article as $key => $value) {
				$currentArticle[$key] = $value;
			}
		}


		return $currentArticle;
	}
}