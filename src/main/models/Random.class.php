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
 * This class is used for getting a random article
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;

class Random {

	private $_locale;

	public function __construct($locale) {
		$this->_locale = $locale;
	}

	public function get() {
		$query = new Query('
			SELECT title, language, status
				FROM {db_prefix}articles
				WHERE status = 1
				AND language = :language
				ORDER BY RAND()
				LIMIT 1
		');

		$query->setParams([
			'language' => $this->_locale
		]);

		$result = $query->execute();

		foreach ($result as $article) {
			return str_replace(' ', '_', $article['title']);
		}
	}
}