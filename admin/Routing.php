<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 *
 * Note: this file depends on the FastRoute routing library.
 */

namespace DraiWiki\admin;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

// Because PHP is such a wonderful and predictable language!
use \FastRoute;

/**
 * Parse the current url and determine whether or not we should load a certain section.
 * @return string The admin section that should be loaded
 */
function createRoutes() {
    $config = Config::instantiate();

	$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) {
	    $routeCollector->get('/section/{title}', 'section');
    });

	// If DraiWiki is placed in a subdirectory, routing will not work, so we shouldn't include the directory in the url
	$uri = $_SERVER['REQUEST_URI'];
	$uri = str_replace($config->read('path', 'BASE_DIRNAME') . '/admin/index.php', '', $uri);

	if (false !== $pos = strpos($uri, '?')) {
	    $uri = substr($uri, 0, $pos);
	}

	$uri = rawurldecode($uri);
	$routeInfo = $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

	if (!empty($routeInfo[1])) {
		return [
			'section' => $routeInfo[1],
			'params' => $routeInfo[2]
		];
	}
	else {
		return [
			'section' => 'home',
			'params' => []
		];
	}
}
