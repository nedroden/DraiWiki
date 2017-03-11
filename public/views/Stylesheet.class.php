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
 * This class is used for parsing CSS files
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\views;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Stylesheet {

	private $_filename, $_baseUrl, $_basePath, $_skinSet, $_imgSet;

	public function __construct($filename, $baseUrl, $basePath, $skinSet, $imgSet) {
		$this->_filename = $filename;
		$this->_baseUrl = $baseUrl;
		$this->_basePath = $basePath;
		$this->_skinSet = $skinSet;
		$this->_imgSet = $imgSet;
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
		return $this->_baseUrl . 'public/views/images/' . $this->_imgSet . '/';
	}

	private function generatePath() {
		return $this->_basePath . 'public/views/skins/' . $this->_skinSet . '/' . $this->_filename . '.css';
	}

	private function doesExist() {
		return file_exists($this->generatePath());
	}
}