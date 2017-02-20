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
 * This class is used for loading pages.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\Article as Model;
use DraiWiki\src\interfaces\App;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Article.class.php';

class Article implements App {

	private $_view, $_template, $_isHome, $_currentPage;

	public function __construct($isHome, $currentPage = null) {
		$this->_isHome = $isHome;
		$this->_currentPage = $currentPage == null || $this->_isHome ? Main::$config->read('wiki', 'WIKI_HOMEPAGE') : $currentPage;

		$this->_view = new View('Article');
		$this->_model = new Model();

		$this->_template = $this->_view->get();
	}

	public function show() {
		$this->_template->setData([
			'article' => $this->_model->retrieve($this->_currentPage, Main::$config->read('wiki', 'WIKI_LOCALE'))
		]);
	}

	public function getTitle() {

	}
}