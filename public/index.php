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

use DraiWiki\src\main\controllers\Main;

ob_start();
set_time_limit(25);

define('REQUIRED_PHP_VERSION', '5.6.0');

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}
else if (version_compare(phpversion(), REQUIRED_PHP_VERSION, '<'))
	die('DraiWiki requires PHP ' . REQUIRED_PHP_VERSION . ' or higher in order to function.');

if (!file_exists(__DIR__ . '/../vendor/autoload.php') || !file_exists(__DIR__ . '/../node_modules/simplemde/dist/simplemde.min.js')) {
	header('Location: NoLibraries.html');
	die;
}

require_once __DIR__ . '/../vendor/autoload.php';
$main = new Main();
$main->load();
