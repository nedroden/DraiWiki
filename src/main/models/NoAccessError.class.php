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

class NoAccessError extends ModelController {

	public function __construct() {
		$this->loadLocale();
		$this->locale->loadFile('error');
	}

	public function get() {
		return [
			'title' => $this->locale->read('error', 'access_denied'),
			'body' => $this->locale->read('error', 'access_denied_message')
		];
	}
}