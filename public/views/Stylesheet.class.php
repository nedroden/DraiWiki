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
 * This class is used to parse CSS files
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\views;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;

class Stylesheet {

	private $_filename;

	public function __construct($filename) {
		$this->_filename = $filename;
	}

	public function parse() {
		if (!$this->doesExist())
			return -1;

		header('Content-type: text/css');
		
		if ($stylesheet = file_get_contents($this->generatePath())) {
			$this->applyImageUrl($stylesheet);

			return $stylesheet;
		}
		else
			return -1;
	}

	private function applyImageUrl(&$stylesheet) {
		$stylesheet = str_replace('{IMAGE_URL}', $this->generateImageUrl(), $stylesheet);
	}

	private function generateImageUrl() {
		return Main::$config->read('path', 'BASE_URL') . 'views/images/' . Main::$config->read('wiki', 'WIKI_IMAGES' . '/');
	}

	private function generatePath() {
		return Main::$config->read('path', 'BASE_PATH') . 'public/views/skins/' . Main::$config->read('wiki', 'WIKI_SKIN') . '/' . $this->_filename . '.css';
	}

	private function doesExist() {
		return file_exists($this->generatePath());
	}
}