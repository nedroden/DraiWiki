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
 * This class determines whether or not the user has access to a certain page
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\controllers\NoAccessError;

class Permission {

	public static function yell() {
		NoAccessError::show();
	}

	public static function checkAndReturn($permission) {
		$user = Registry::get('user');
		return $user->hasPermission($permission);
	}
}
