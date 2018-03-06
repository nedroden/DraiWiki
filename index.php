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

declare(strict_types = 1);
define('DraiWiki', 1);
define('REQUIRED_PHP_VERSION', '7.1.0');

if (version_compare(phpversion(), REQUIRED_PHP_VERSION, '<'))
	die('DraiWiki requires PHP ' . REQUIRED_PHP_VERSION . ' or higher in order to function.');

require 'autoload.php';
require 'public/index.php';
