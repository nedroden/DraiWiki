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
session_start();
ob_start();

set_time_limit(25);

use DraiWiki\src\main\controllers\Main;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}
else if (version_compare(phpversion(), '5.6.0', '<'))
	die('KeyBlog requires PHP 5.6 or higher in order to function.');	

define('DraiWikiVersion', '1.0 Alpha 1');

require 'src/main/controllers/Main.class.php';
$main = new Main();
$main->init();