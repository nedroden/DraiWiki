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
 * This class is used for setting up the administration panel.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\admin\controllers;

if (!defined('DraiWikiAdmin')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\Config;
use DraiWiki\src\auth\models\User;
use DraiWiki\src\database\controllers\Connection;
use DraiWiki\src\database\models\SessionHandler;
use DraiWiki\src\main\controllers\SettingsImporter;

require_once '../public/Config.php';

class Admin {

	public static $config;

	private $_currentPage;

	public function __construct() {
		self::$config = new Config();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Connection.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Query.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/models/SessionHandler.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/auth/models/User.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/auth/controllers/Permission.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/App.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/Error.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/NoAccessError.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/SettingsImporter.class.php';

		Connection::instantiate();
		SettingsImporter::import();

		new SessionHandler();
		session_start();

		$this->_user = User::instantiate();
		Locale::instantiate();
	}

	public function display() {
		echo '<h1>Administration panel</h1>This is just a placeholder.';
	}
}