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

require 'public/Config.php';

class Main {

	public static $config;

	private $_currentApp, $_currentAppName;

	private $_apps = [
		'home' => [
			'package' => 'main',
			'class' => 'Home'
		]
	];

	public function __construct() {
		self::$config = new Config();
		$this->setCurrentApp();

		require_once self::$config->read('path', 'BASE_PATH') . 'public/views/View.class.php';
		require_once self::$config->read('path', 'BASE_PATH') . 'src/interfaces/App.interface.php';
	}

	public function init() {
		$this->loadApp($this->_currentAppName);
	}

	private function loadApp($app) {
		require_once self::$config->read('path', 'BASE_PATH') . '/src/' . $this->_apps[$app]['package'] . '/controllers/' . $this->_apps[$app]['class'] . '.class.php';

		$classname = '\DraiWiki\src\\' . $this->_apps[$app]['package'] . '\controllers\\' . $this->_apps[$app]['class'];
		$this->_currentApp = new $classname(); 
	}

	private function getCurrentApp() {
		if (!empty($_GET['app']) && array_key_exists(strtolower($_GET['app']), $this->_app))
			return $_GET['app'];
		else
			return 'home';
	}

	private function setCurrentApp() {
		$this->_currentAppName = $this->getCurrentApp();
	}
}