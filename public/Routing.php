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
 * @return array The app name + its parameters
 */
function createRoutes() : array {

	$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) {
        // AJAX
        $routeCollector->get('/management/{subapp}/ajax/{ajax_request}', 'management');
        $routeCollector->get('/management/{subapp}/{section}/ajax/{ajax_request}', 'management');
        $routeCollector->addRoute(['GET', 'POST'], '/article/{title}/{action}/ajax/{ajax_request}', 'article');
        $routeCollector->get('/find/ajax/{ajax_request}', 'findarticle');

        $routeCollector->get('/stylesheet/{id}', 'stylesheet');

	    $routeCollector->get('/activate/{code}', 'activate');
	    $routeCollector->get('/article/{title}', 'article');
		$routeCollector->addRoute(['GET', 'POST'], '/article/{title}/{action}', 'article');
		$routeCollector->addRoute(['GET', 'POST'], '/article/{title}/{action}/{id}', 'article');

		$routeCollector->addRoute(['GET', 'POST'], '/find', 'findarticle');

        $routeCollector->post('/locale', 'changelocale');
		$routeCollector->addRoute(['GET', 'POST'], '/locale/{code}', 'changelocale');
		$routeCollector->get('/locale/{code}/{article}', 'changelocale');

		$routeCollector->get('/resources', 'resources');
		$routeCollector->get('/image/{filename}', 'imageviewer');
        $routeCollector->addRoute(['GET', 'POST'], '/account/{section}', 'account');
        $routeCollector->addRoute(['GET', 'POST'], '/account/{section}/{id}', 'account');

		$routeCollector->addRoute(['GET', 'POST'], '/imageupload', 'imageupload');

        $routeCollector->get('/management', 'management');
        $routeCollector->addRoute(['GET', 'POST'],'/management/{subapp}', 'management');
        $routeCollector->addRoute(['GET', 'POST'],'/management/{subapp}/{section}', 'management');
        $routeCollector->addRoute(['GET', 'POST'],'/management/{subapp}/{action}/{id}', 'management');

		$routeCollector->get('/random', 'random');

	    $routeCollector->addRoute(['GET', 'POST'], '/register', 'register');
	    $routeCollector->addRoute(['GET', 'POST'], '/login', 'login');
	    $routeCollector->get('/logout', 'logout');
	});

	// We need to do a few more things if we've placed DraiWiki in a subdirectory
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

	$uri = rawurldecode(rtrim($uri, '/'));
	$routeInfo = $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

	if (!empty($routeInfo[1])) {
		return [
			'app' => $routeInfo[1],
			'params' => $routeInfo[2] ?? []
		];
	}
	else {
		return [
			'app' => 'article',
			'params' => []
		];
	}
}
