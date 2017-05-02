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

use DraiWiki\src\core\controllers\Registry;

/**
 * This class contains some userful functions for models, such as getting the
 * locale and loading the current user. This abstract class does not contain
 * database methods. Instead, if you want to execute a query, you should use
 * the appropriately named 'Query' class.
 *
 * @since		1.0 Alpha 1
 */
abstract class ModelController {

	/**
	 * @var Locale $locale An object of the current language.
	 * @var User $user The object that belongs to the current user (or guest).
	 */
	protected $locale, $user;

	protected function __construct() {

	}

	protected function loadLocale() {
		$this->locale = Registry::get('locale');
	}

	protected function loadUser() {
		$this->user = Registry::get('user');
	}
}
