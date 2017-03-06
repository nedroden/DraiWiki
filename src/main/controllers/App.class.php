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
 * This class is used for loading pages.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

abstract class App {

	protected $hasStylesheet = false;

	public function getTitle() {
		return null;
	}

	public function getStylesheets() {
		return [];
	}

	public function getSubmenuItems() {
		return [];
	}

	public function getHasStylesheet() {
		return $this->hasStylesheet;
	}

	public function getHeader() {
		return null;
	}
}