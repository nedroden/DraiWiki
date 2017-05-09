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
use DraiWiki\src\core\controllers\Registry;

require_once 'public/Config.php';
require_once 'public/Routing.php';

class Main {

    private $_config;

    public function __construct() {
        $this->_config = Registry::set('config', new Config());
    }

    public function load() {

    }
}
