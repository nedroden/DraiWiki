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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\Config;
use DraiWiki\src\core\controllers\Connection;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\core\controllers\SelectQuery;
use DraiWiki\src\core\models\RouteInfo;
use DraiWiki\src\main\controllers\App;
use DraiWiki\src\main\controllers\GUI;
use DraiWiki\src\main\controllers\Locale;

use function DraiWiki\createRoutes;

require_once 'public/Config.php';
require_once 'public/Routing.php';

class Main {

	/**
	 * @var Config $_config This object stores important settings
	 */
    private $_config;

	/**
	 * @var array $_route This array contains information about the current url
	 */
	private $_route;

	private $_locale;

	const WIKI_VERSION = '1.0 Alpha 1';

    public function __construct() {
        $this->_config = Registry::set('config', new Config());

		$this->_route = Registry::set('route', new RouteInfo(createRoutes()));
    }

    public function load() {
		Registry::set('connection', new Connection());

		$this->_locale = Registry::set('locale', new Locale());

		$gui = new GUI();
		$gui->setData([
			'url' => $this->_config->read('url'),
			'wiki_name' => $this->_config->read('wiki_name'),
			'locale_native' => $this->_locale->getNative(),
			'locale_copyright' => $this->_locale->getCopyright()
		]);

		$app = new App();
		$app->execute();

		$gui->showHeader();
		$app->display();
		$gui->showFooter();
    }
}
