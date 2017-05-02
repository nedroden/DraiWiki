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

namespace DraiWiki\admin\controllers;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

/**
 * This class loads the requested template.
 *
 * @since		1.0 Alpha 1
 */
class ViewController {

    private $_name, $_config;

    public function __construct($name) {
        $this->_name = ucfirst($name);
        $this->_config = Registry::get('conf_admin');
    }

    private function getImageLink() {
		return $this->_config->read('path', 'BASE_ADMIN_PATH') . 'public/views/images/' . $this->_config->read('wiki', 'WIKI_IMAGES') . '/';
	}

	private function getStylesheet() {
		return $this->_config->read('path', 'BASE_ADMIN_PATH') . 'stylesheet.php?id=' . lcfirst($this->_name);
	}

	public function get() {
		if (file_exists($this->_config->read('path', 'BASE_ADMIN_PATH') . '/views/' . $this->_name . '.template.php'))
			require_once $this->_config->read('path', 'BASE_ADMIN_PATH') . '/views/' . $this->_name . '.template.php';
		else
			die('<h1>Template not found</h1>Aborting...');

		$tplName = 'DraiWiki\admin\views\\' . $this->_name;
		return new $tplName($this->getImageLink(), $this->getStylesheet());
	}
}
