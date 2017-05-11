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

namespace DraiWiki\src\core\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\Connection;

abstract class Query {

    private $_connection, $_query;

    public function __construct($query) {
        $this->_connection = Registry::get('connection');
        $this->_query = $query;
    }

    protected function execute() {

    }

    public function toString() {
        return $this->_query;
    }
}
