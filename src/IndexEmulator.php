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
use DraiWiki\src\core\controllers\Connection;

define('DraiWiki', 1);

require __DIR__ . '/../public/Config.php';
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
    $config = new Config();
}

function connectToDatabase(?Connection &$connection) : void {

}