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
use DraiWiki\src\admin\models\User;
use DraiWiki\src\admin\controllers\Connection;
use DraiWiki\src\database\models\SessionHandler;
use DraiWiki\src\admin\controllers\SettingsImporter;

/**
 * NOTE: as you may notice, the admin panel only shares two classes with
 * the wiki front end. Other required classes also used by the aforementioned
 * wiki front end, are duplicated. This is for a very simple reason: DraiWiki
 * encourages admins to make changes to the code so that it suits their needs.
 * This means that the admin panel has a section in which it is very easy to
 * make changes to the code without having to download the file before you
 * can edit them and reupload them afterwards. So why is this relevant?
 * It's quite simple: normally, if something went wrong, you'd have to log on
 * to FTP, download the files, find out what's wrong, fix the files and
 * reupload them. So if you mess up a file that's required by both the admin
 * panel and the front end, you wouldn't be able to fix it from the admin
 * panel. However, since DraiWiki's admin panel for the most part has its own
 * files and it doesn't allow you to edit files used by the admin panel,
 * there wouldn't be a problem.
 */

require_once '../public/Config.php';

class Admin {

	public static $config;

	private $_currentPage;

	public function __construct() {
		self::$config = new Config();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'src/admin/controllers/Query.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/admin/models/User.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/models/SessionHandler.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/admin/controllers/SettingsImporter.class.php';

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