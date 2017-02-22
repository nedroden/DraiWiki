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
 * This class contains several useful methods.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\main\controllers\Main;

class AuthTool {

	public static function hash($value) {
		$salt = Main::$config->read('user', 'SALT');

		$value .= $salt;
		$value = hash('sha256', $salt . $value);
		$value = hash('sha512', $value. $salt);
		return $value;
	}
}