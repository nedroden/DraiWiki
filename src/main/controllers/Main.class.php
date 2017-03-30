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
 * This class is used for loading the other required classes and setting up the wiki.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\Config;
use DraiWiki\src\auth\models\User;
use DraiWiki\src\database\controllers\Connection;
use DraiWiki\src\database\models\SessionHandler;
use DraiWiki\src\main\controllers\SettingsImporter;
use DraiWiki\src\main\models\Menu;
use DraiWiki\src\main\models\SidebarMenu;
use DraiWiki\src\main\models\Locale;
use DraiWiki\views\View;

use function DraiWiki\createRoutes;

require_once 'public/Config.php';
require_once 'public/Routing.php';

class Main {

	/**
	 * @var Config $config An instance of the config class containing the settings.
	 */
	public static $config;

	/**
	 * @var App $_currentApp The object that belongs to the app we've loaded
	 * @var string $_currentAppName The name of the app we're supposed to load based on the URL
	 */
	private $_currentApp, $_currentAppName;

	/**
	 * @var User $_user An object of the user class. This object contains the current user information
	 */
	private $_user;

	/**
	 * @var array $_route This array contains data related to the request URL, such as the current app
	 */
	private $_route;

	/**
	 * @var array $_apps An array containing the 'apps' that are available.
	 */
	private $_apps = [
		'article' => [
			'package' => 'main',
			'class' => 'Article'
		],
		'login' => [
			'package' => 'auth',
			'class' => 'Login'
		],
		'logout' => [
			'package' => 'auth',
			'class' => 'Logout'
		],
		'register' => [
			'package' => 'auth',
			'class' => 'Registration'
		],
		'random' => [
			'package' => 'main',
			'class' => 'Random'
		]
	];

	/**
	 * This method loads the necessary files and creates a new database connection.
	 * @return void
	 */
	public function __construct() {
		self::$config = new Config();

		$this->_route = createRoutes();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Connection.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/ModelController.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Query.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/models/SessionHandler.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/auth/controllers/Permission.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/auth/models/User.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/Template.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/View.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/App.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/Error.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/NoAccessError.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/SettingsImporter.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/Menu.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/SidebarMenu.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/Locale.class.php';

		SettingsImporter::import();

		new SessionHandler();
		session_start();

		$this->_user = User::instantiate();
		Locale::instantiate();
	}

	/**
	 * This method makes sure we have something to work with and then proceeds to show content to the screen.
	 * @return void
	 */
	public function init() {
		$this->loadApp($this->_currentAppName);

		$view = new View('Index');
		$menu = new Menu();
		$sidebarMenu = new SidebarMenu();

		$template = $view->get();

		if ($this->_currentApp->getHasStylesheet())
			$template->pushStylesheets($this->_currentApp->getStylesheets());

		if (!empty($this->_currentApp->getTitle()))
			$template->setData(['title' => $this->_currentApp->getTitle()]);

		if (!empty($this->_currentApp->getSubmenuItems()))
			$sidebarMenu->addItems($this->_currentApp->getSubmenuItems());

		if (!empty($this->_currentApp->getHeader()))
			$template->setData(['header' => $this->_currentApp->getHeader()]);

		$template->setUserInfo($this->_user->get());

		$template->pushMenu($menu->get());
		$template->pushSidebarMenu($sidebarMenu->get());

		$template->showHeader();
		$this->_currentApp->show();
		$template->showFooter();
	}

	/**
	 * This method loads the correct files for an app and creates a new instance of the corresponding class.
	 * @param string $app The name of the app
	 * @return void
	 */
	private function loadApp($app) {
		require_once self::$config->read('path', 'BASE_PATH') . 'src/' . $this->_apps[$app]['package'] . '/controllers/' . $this->_apps[$app]['class'] . '.class.php';

		$classname = '\DraiWiki\src\\' . $this->_apps[$app]['package'] . '\controllers\\' . $this->_apps[$app]['class'];

		// I'm sorry. I'm so, so sorry.
		if ($this->_currentAppName != 'article')
			$this->_currentApp = new $classname();
		else if ($this->_currentAppName == 'article' && empty($this->_route['params']['title']))
			$this->_currentApp = new $classname(null);
		else
			$this->_currentApp = new $classname($this->_route['params']['title'], (!empty($this->_route['params']['action']) && $this->_route['params']['action'] == 'edit'));
	}

	/**
	 * Determines the app that should be loaded, based on the value of '_route['app']'
	 * @return string The name of the app that should be loaded
	 */
	private function getCurrentApp() {
		return !empty($this->_route['app']) && array_key_exists(strtolower($this->_route['app']), $this->_apps) ? $this->_route['app'] : 'article';
	}

	/**
	 * This method sets the name of the current app based on the return value of getCurrentApp()
	 * @return void
	 */
	private function setCurrentApp() {
		$this->_currentAppName = $this->getCurrentApp();
	}
}
