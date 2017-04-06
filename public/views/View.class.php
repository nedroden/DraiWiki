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
 * Class information:
 * This class is used for loading a view. It automatically loads the correct files.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\views;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;

class View {

	private $_name;

	public function __construct($name) {
		$this->_name = ucfirst($name);
	}

	private function getImageLink() {
		return Main::$config->read('path', 'BASE_URL') . 'public/views/images/' . Main::$config->read('wiki', 'WIKI_IMAGES') . '/';
	}

	private function getStylesheet() {
		return Main::$config->read('path', 'BASE_URL') . 'stylesheet.php?id=' . lcfirst($this->_name);
	}

	public function get() {
		if (file_exists(Main::$config->read('path', 'BASE_PATH') . 'public/views/templates/' . Main::$config->read('wiki', 'WIKI_TEMPLATES') . '/' . $this->_name . '.template.php'))
			require_once Main::$config->read('path', 'BASE_PATH') . 'public/views/templates/' . Main::$config->read('wiki', 'WIKI_TEMPLATES') . '/' . $this->_name . '.template.php';
		else if (Main::$config->read('wiki', 'WIKI_TEMPLATES') != 'default' && file_exists(Main::$config->read('path', 'BASE_PATH') . 'public/views/templates/default/' . $this->_name . '.template.php'))
			require_once Main::$config->read('path', 'BASE_PATH') . 'public/views/templates/default/' . $this->_name . '.template.php';
		else
			die('<h1>Template not found</h1>Aborting...');

		$tplName = 'DraiWiki\views\templates\\' . $this->_name;
		return new $tplName($this->getImageLink(), $this->getStylesheet());
	}
}
