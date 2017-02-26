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
 * This class is called when a user doesn't have access to a certain page.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\auth\models\User;
use \DraiWiki\src\main\controllers\Main;
use \DraiWiki\src\main\models\Menu;
use \DraiWiki\src\main\models\NoAccessError as Model;
use \DraiWiki\views\View;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/NoAccessError.class.php';

class NoAccessError {

	public static function show() {
		ob_clean();
		$model = new Model();
		$userInfo = User::instantiate();

		$indexView = new View('Index');
		$errorView = new View('NoAccessError');
		$menu = new Menu();

		$indexTemplate = $indexView->get();
		$errorTemplate = $errorView->get();

		$indexTemplate->setUserInfo($userInfo->get());
		$indexTemplate->pushMenu($menu->get());
		$errorTemplate->setData($model->get());

		$indexTemplate->showHeader();
		$errorTemplate->show();
		$indexTemplate->showFooter();
		die;
	}
}