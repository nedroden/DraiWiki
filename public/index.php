<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

use DraiWiki\src\main\controllers\Main;

ob_start();
set_time_limit(25);

ini_set('session.cookie_lifetime', 10 * 365 * 24 * 60 * 60);

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

if (!file_exists(__DIR__ . '/../vendor/autoload.php') || !file_exists(__DIR__ . '/../node_modules/simplemde/dist/simplemde.min.js')) {
	header('Location: NoLibraries.html');
	die;
}

/* This will enable detailed error messages before the permissions are loaded. Keep in mind that
 * this means detailed error messages will be visible to everyone. Once permissions are loaded,
 * permissions will be used to determine whether or not debug information will be shown.
 */
const DEBUG_ALWAYS = true;

require_once __DIR__ . '/../vendor/autoload.php';
$main = new Main();
$main->load();
