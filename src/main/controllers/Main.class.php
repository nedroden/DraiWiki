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
use DraiWiki\views\Stylesheet;
use DraiWiki\views\View;
use DraiWiki\src\database\controllers\Connection;
use DraiWiki\src\main\controllers\SettingsImporter;
use DraiWiki\src\main\models\Menu;
use DraiWiki\src\main\models\Locale;

require 'public/Config.php';

class Main {

	public static $config;

	private $_currentApp, $_currentAppName;

	private $_apps = [
		'admin' => [
			'package' => 'admin',
			'class' => 'Main'
		],
		'article' => [
			'package' => 'main',
			'class' => 'Article'
		],
		'login' => [
			'package' => 'auth',
			'class' => 'Login'
		],
		'register' => [
			'package' => 'auth',
			'class' => 'Registration'
		]
	];

	/**
	 * This method loads the necessary files and creates a new database connection.
	 * @return void
	 */
	public function __construct() {
		self::$config = new Config();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Connection.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/ModelController.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Query.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/Template.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/View.class.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/interfaces/App.interface.php';

		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/Error.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/controllers/SettingsImporter.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/Menu.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/Locale.class.php';

		Connection::instantiate();
		SettingsImporter::import();
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

		$template = $view->get();

		if ($this->_currentApp->getHasStylesheet())
			$template->pushStylesheets($this->_currentApp->getStylesheets());

		if (!empty($this->_currentApp->getTitle()))
			$template->setData(['title' => $this->_currentApp->getTitle()]);

		$template->pushMenu($menu->get());

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

		if ($this->_currentAppName != 'article')
			$this->_currentApp = new $classname();
		else if ($this->_currentAppName == 'article' && empty($_GET['article']))
			$this->_currentApp = new $classname(true);
		else
			$this->_currentApp = new $classname(false, $_GET['article']);
	}

	/**
	 * Determines the app that should be loaded, based on the value of _GET['app'].
	 * @return string The name of the app that should be loaded
	 */
	private function getCurrentApp() {
		if (!empty($_GET['app']) && array_key_exists(strtolower($_GET['app']), $this->_apps))
			return $_GET['app'];
		else
			return 'article';
	}

	/**
	 * This method sets the name of the current app based on the return value of getCurrentApp()
	 * @return void
	 */
	private function setCurrentApp() {
		$this->_currentAppName = $this->getCurrentApp();
	}
}