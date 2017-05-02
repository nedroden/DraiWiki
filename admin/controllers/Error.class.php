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

namespace DraiWiki\admin\controllers;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\admin\controllers\ViewController;
use DraiWiki\admin\models\Error as Model;

/**
 * This class displays error messages.
 *
 * @since		1.0 Alpha 1
 */
class Error {

	private $_model, $_template;

	public function __construct($message, $parameters, $languageFallback = false) {
		require_once __DIR__ . '/../models/Error.class.php';

		$this->_model = new Model($message, $parameters, $languageFallback);

		$viewController = new ViewController('error');
		$this->_template = $viewController->get();

		$this->_template->setData([
			'title' => $this->_model->getTitle(),
			'message' => $this->_model->getMessage()
		]);
	}

	public function show() {
		ob_end_clean();
		$this->_template->show();

		die;
	}
}
