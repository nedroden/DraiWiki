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

define('DraiWiki', 1);

require_once 'public/Config.php';

use DraiWiki\Config;
use DraiWiki\views\Stylesheet;

if (!empty($_GET['id'])) {
	$config = new Config();

	$baseUrl = $config->read('url');
	$basePath = $config->read('path');

	$imgSet = $config->read('images');
	$skinSet = $config->read('skins');

	require_once $basePath . '/src/main/models/Stylesheet.class.php';

	$stylesheet = new Stylesheet(ucfirst($_GET['id']), $baseUrl, $basePath, $skinSet, $imgSet);
	echo $stylesheet->parse();
	die;
}
