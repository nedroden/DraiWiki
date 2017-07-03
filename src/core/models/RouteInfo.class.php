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

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class RouteInfo {

    private $_route, $_app, $_params;

    public function __construct(array $route) {
        $this->_route = $route;

        $this->_app = !empty($route['app']) ? $route['app'] : 'article';
        $this->_params = !empty($route['params']) ? $route['params'] : [];
    }

    public function getApp() {
    	return $this->_app;
    }

    public function getParams() {
    	return $this->_params;
    }
}
