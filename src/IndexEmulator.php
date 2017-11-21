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

use DraiWiki\Config;
use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\{Connection, Registry};
use DraiWiki\src\core\models\SettingsImporter;
use DraiWiki\src\main\controllers\Locale;

define('EntryPointEmulation', 1);
define('DraiWiki', 1);
define('DEBUG_ALWAYS', false);

require __DIR__ . '/../src/core/models/ConfigHeader.class.php';
require __DIR__ . '/../public/Config.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../autoload.php';

/**
 * The purpose of this file is to allow DraiWiki's classes to be used
 * outside the normal entry point (which is index.php). For example,
 * the image dispatcher is run by going to ImageDispatch.php. Since
 * the regular entry point is not used, it's difficult to do things
 * like establishing a database connection. This file fixes that problem
 * by emulating the index.php file. Thus, database connections can be
 * established.
 * @param Config $config
 */

function start(?Config &$config) : void {
    $config = Registry::set('config', new Config());
}

function connectToDatabase(?Connection &$connection, bool $loadSettings = true) : void {
    $connection = Registry::set('connection', new Connection());

    if ($loadSettings)
        SettingsImporter::execute();
}

function loadEnvironment() : void {
    $user = Registry::set('user', new User());
    Registry::set('locale', new Locale());

    $user->updateInfoWithLocale();
}