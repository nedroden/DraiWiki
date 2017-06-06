<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\models\AppHeader;
use DraiWiki\src\main\models\Article as Model;

class Article extends AppHeader {

	private $_model;

	public function __construct(?string $title, bool $isHomepage = false) {
		$this->_model = new Model($title, $isHomepage);
	}
}