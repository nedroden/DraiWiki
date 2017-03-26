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
 * This class redirects the user to a random article
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\main\controllers\Main;
use \DraiWiki\src\main\models\Locale;
use \DraiWiki\src\main\models\Random as Model;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Random.class.php';

class Random {

	private $_model, $_locale;

	public function __construct() {
		$this->_locale = Locale::instantiate();
		$this->_model = new Model($this->_locale->getLanguage()['code']);
		$this->handle();
	}

	public function handle() {
		$article = $this->_model->get();
		$this->redirect($article);
	}

	public function redirect($article) {
		header('Location: ' . Main::$config->read('path', 'BASE_URL') . 'index.php/article/' . $article);
		die();
	}
}
