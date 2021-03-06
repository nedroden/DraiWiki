<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\Config;
use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\{Connection, ModuleLoader, Registry};
use DraiWiki\src\core\models\{RouteInfo, SessionHandler, SettingsImporter};
use DraiWiki\src\main\models\{DebugBarWrapper, Stylesheet};

use function DraiWiki\createRoutes;

require_once __DIR__ . '/../../../public/Config.php';
require_once __DIR__ . '/../../../public/Routing.php';
require_once __DIR__ . '/../../../modules/Hook.class.php';
require_once __DIR__ . '/../../../modules/Module.interface.php';
require_once __DIR__ . '/../../ScriptExtensions.php';
require_once __DIR__ . '/../../WrapperFunctions.php';

class Main {

	/**
	 * @var Config $_config This object stores important settings
	 */
    private $_config;

	/**
	 * @var array $_route This array contains information about the current url
	 */
	private $_route;

    /**
     * @var Locale $_locale The locale object; load it here because we might need it later
     */
	private $_locale;

	public const WIKI_VERSION = '1.0 Alpha 1';
    public const DW_SUPPORT_URL = 'https://draiwiki.robertmonden.com';
    public const IS_DEV_BUILD = true;

    public function __construct() {
        DebugBarWrapper::create();
        $this->_config = Registry::set('config', new Config());
        $this->_route = Registry::set('route', new RouteInfo(createRoutes()));

        DebugBarWrapper::report('Settings and route info loaded');
    }

    public function load() : void {
		Registry::set('connection', new Connection());

		$moduleLoader = new ModuleLoader();
	    if ($moduleLoader->canLoadModules()) {
            $moduleLoader->scan();
	        $moduleLoader->loadAll();
        }

        SettingsImporter::execute();

		new SessionHandler();

		if ($this->_route->getApp() == 'stylesheet')
		    $this->loadStylesheet();

        $user = Registry::set('user', new User());
        $this->_locale = Registry::set('locale', new Locale());
        $user->updateInfoWithLocale();

		$gui = Registry::set('gui', new GUI());
		$gui->setData([
			'url' => $this->_config->read('url'),
			'wiki_name' => $this->_config->read('wiki_name'),
			'locale_native' => $this->_locale->getCurrentLocaleInfo()->getNative(),
			'locale_copyright' => $this->_locale->getCurrentLocaleInfo()->getCopyright()
		]);

		$app = new App();
		$app->execute();

		$headerContext = $app->getHeaderContext();
		$gui->setData($headerContext);

		if ($headerContext['ignore_templates'] != 'both' && $headerContext['ignore_templates'] != 'header')
		    $gui->showHeader();

		$app->display();

        if ($headerContext['ignore_templates'] != 'both' && $headerContext['ignore_templates'] != 'footer')
		    $gui->showFooter();
    }

    public function loadStylesheet() : void {
        $stylesheet = new Stylesheet($this->_route->getParams()['id']);
        echo $stylesheet->parse();
        die;
    }
}
