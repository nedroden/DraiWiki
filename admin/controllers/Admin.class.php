<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\admin\controllers;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\admin\Config;

use function \DraiWiki\admin\createRoutes;

/**
 * This class' sole purpose is setting up the admin panel. It loads all other
 * required files and points unauthorized users to the door. Or the login page.
 * Or both. I'll stop now.
 *
 * @since		1.0 Alpha 1
 */
class Admin {

	/**
	 * @var array $_route Current URL information, used for detecting the current app.
	 */
	private $_route;

	/**
	 * @var string $_dir The location of the index.php belonging to the admincp.
	 */
	private $_dir;

	/**
	 * The apps that can be loaded. Usage: 'app_name' => 'app_class'
	 * @var array $_actions The list of apps
	 */
	private $_apps = [
		'home' => 'Home',
	];

	/**
	 * The app name and its object.
	 * @var string $_appName The name of the app
	 * @var App $_appObject The app's object
	 */
	private $_appName, $_appObject;

	public function __construct($directory) {
		$this->_dir = $directory;

        require_once $this->_dir . '/../vendor/autoload.php';
		require_once $this->_dir . '/Config-Admin.php';
        require_once $this->_dir . '/Routing.php';
        require_once $this->_dir . '/../src/core/controllers/Registry.class.php';

        Registry::set('conf_admin', new Config());

        $this->_route = createRoutes();
		$this->_appName = $this->getCurrentApp();
	}

	public function display() {
		echo 'This is the admin panel. Isn\'t it cool?';
	}

	private function getCurrentApp() {

	}
}
