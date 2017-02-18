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
 * This class is used for importing settings from the database.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\Config as Model;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/main/models/Config.class.php';

class SettingsImporter {

	public static function import() {
		foreach (Model::retrieve() as $setting) {
			Main::$config->import($setting['category'], $setting['identifier'], $setting['value']);
		}
	}
}