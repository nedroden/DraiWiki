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
 * This class validates the user's input in the registration page form.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;

class Registration extends ModelController {

	public function __construct() {
		$this->loadLocale();
		$this->locale->loadFile('registration');
	}

	public function getAction() {
		return Main::$config->read('path', 'BASE_URL') . 'index.php?app=register';
	}

	public function getTitle() {
		return $this->locale->read('registration', 'page_title');
	}
}