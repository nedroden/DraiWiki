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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\database\controllers\ModelController;
use DraiWiki\src\main\controllers\Main;

class Error extends ModelController {

	private $_title, $_body, $_detailedInfo;

	public function __construct($detailedInfo, $parameters, $langFallBack = false) {
		if (!$langFallBack)
			$this->loadLocale();

		$this->_title = $langFallBack ? $locale->read('Error', 'an_error_occurred') : 'Fatal error';
		$this->_body = $langFallBack ? $locale->read('Error', 'an_error_occurred_message') : 'A fatal error has just occured, and for security reasons, the script has been aborted. We apologize for any inconvenience this is causing. Try refreshing the page to see if this error has been resolved. If not, please contact the administrator.';
		$this->_detailedInfo = Main::$config->read('debug', 'ENABLE_DEBUG') ? $detailedInfo : null;
	}

	public function retrieve() {
		return [
			'title' => $this->_title,
			'body' => $this->_body,
			'detailed' => $this->_detailedInfo
		];
	}
}