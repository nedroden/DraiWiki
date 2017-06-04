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

namespace DraiWiki;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

// Because PHP is such a wonderful and predictable language!
use FastRoute;

/**
 * Parse the current url and determine whether or not we should load a certain app.
 * @return string The app that should be loaded
 */
function createRoutes() {

	$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) {
	    $routeCollector->get('/article/{title}', 'article');
		$routeCollector->addRoute(['GET', 'POST'], '/article/{title}/{action}', 'article');

		$routeCollector->get('/locale/{locale}', 'changelocale');
		$routeCollector->get('/locale/{locale}/{article}', 'changelocale');

		$routeCollector->get('/random', 'random');

	    $routeCollector->addRoute(['GET', 'POST'], '/register', 'register');
	    $routeCollector->addRoute(['GET', 'POST'], '/login', 'login');
	    $routeCollector->get('/logout', 'logout');
	});

	// If DraiWiki is placed in a subdirectory, routing will not work, so we shouldn't include the directory in the url
	$uri = $_SERVER['REQUEST_URI'];
	$subdirs = explode('/', parse_url($uri, PHP_URL_PATH));
	$currentLocation = '';

	foreach ($subdirs as $subdir) {
		if (stripos($subdir, 'index.php') !== false)
			break;
		else if (empty($subdir))
			continue;
		else
			$currentLocation .= '/' . $subdir;
	}

	$uri = str_ireplace($currentLocation . '/index.php', '', $uri);

	if (false !== $pos = strpos($uri, '?')) {
	    $uri = substr($uri, 0, $pos);
	}

	$uri = rawurldecode($uri);
	$routeInfo = $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

	if (!empty($routeInfo[1])) {
		return [
			'app' => $routeInfo[1],
			'params' => (!empty($routeInfo[2]) ? $routeInfo[2] : [])
		];
	}
	else {
		return [
			'app' => 'article',
			'params' => []
		];
	}
}
