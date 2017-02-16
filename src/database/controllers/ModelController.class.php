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
 * This class allows models to retrieve data from the database.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\database\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\database\controllers\Query;
use DraiWiki\src\main\models\Locale;

abstract class ModelController {

	protected $locale;

	protected function __construct() {

	}

	protected function loadLocale() {
		$this->locale = Locale::instantiate();
	}
}