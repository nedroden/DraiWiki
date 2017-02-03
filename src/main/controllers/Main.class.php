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
use DraiWiki\src\main\models\Menu;

require 'public/Config.php';

class Main {

	public static $config;

	private $_currentApp, $_currentAppName;

	private $_apps = [
		'article' => [
			'package' => 'main',
			'class' => 'Article'
		]
	];

	public function __construct() {
		self::$config = new Config();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'src/database/controllers/Connection.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/Template.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/View.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/interfaces/App.interface.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/main/models/Menu.class.php';

		Connection::instantiate();
	}

	public function init() {
		if (!empty($_GET['stylesheet'])) {
			require_once self::$config->read('path', 'BASE_PATH') . 'public/views/Stylesheet.class.php';
			$stylesheet = new Stylesheet(ucfirst($_GET['stylesheet']));
			echo $stylesheet->parse();
			die;
		}

		$this->loadApp($this->_currentAppName);

		$view = new View('Index');
		$menu = new Menu();

		$template = $view->get();
		$template->pushMenu($menu->get());

		$template->showHeader();
		$this->_currentApp->show();
		$template->showFooter();
	}

	private function loadApp($app) {
		require_once self::$config->read('path', 'BASE_PATH') . '/src/' . $this->_apps[$app]['package'] . '/controllers/' . $this->_apps[$app]['class'] . '.class.php';

		$classname = '\DraiWiki\src\\' . $this->_apps[$app]['package'] . '\controllers\\' . $this->_apps[$app]['class'];

		if (!$this->_currentAppName == 'article')
			$this->_currentApp = new $classname();
		else if ($this->_currentAppName == 'article' && empty($_GET['article']))
			$this->_currentApp = new $classname(true);
		else
			$this->_currentApp = new $classname(false, $_GET['article']);
	}

	private function getCurrentApp() {
		if (!empty($_GET['app']) && array_key_exists(strtolower($_GET['app']), $this->_apps))
			return $_GET['app'];
		else
			return 'article';
	}

	private function setCurrentApp() {
		$this->_currentAppName = $this->getCurrentApp();
	}
}