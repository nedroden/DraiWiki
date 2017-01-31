<?php
/**
 * HURRICANE
 * DraiWiki default theme
 *
 * @author		Robert Monden
 * @copyright	DraiWiki development team, 2017
 * @version		1.0 Alpha 1
 */

namespace DraiWiki\views\templates;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\views\Template;

class Index extends Template {

	public function showHeader() {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>', Main::$config->read('wiki', 'WIKI_NAME'),' | ', Main::$config->read('wiki', 'WIKI_SLOGAN'), '</title>
		<link rel="stylesheet" type="text/css" href="', $this->getStylesheet('Index'), '" />
	</head>
	<body>
		Test test test';
	}

	public function showFooter() {
echo '
	</body>
</html>';
	}
}