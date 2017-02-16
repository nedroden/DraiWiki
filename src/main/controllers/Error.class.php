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
 * This class is used for generating error pages.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\Error as Model;
use DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Error.class.php';

class Error {

	private $_model, $_view, $_langFallback, $_template;

	public function __construct($detailedInfo = null, $parameters = [], $langFallback = false) {
		$this->_langFallback = $langFallback;
		$this->_model = new Model($detailedInfo, $parameters, $langFallback);
		$this->_view = new View('Error');

		$this->_template = $this->_view->get();
	}

	public function show() {
		$this->_template->setData([
			'error' => $this->_model->retrieve()
		]);

		$this->_template->showHeader();
		$this->_template->showBody();
		$this->_template->showHeader();
	}
}