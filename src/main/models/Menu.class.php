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
 * This class is used for generating the menu.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Menu {

	private $_items = [];

	public function __construct() {
		$this->set();
	}

	public function get() {
		return $this->_items;
	}

	private function set() {
		$this->_items = [
			'home' => [
				'label' => 'Home',
				'href' => 'index.php',
				'visible' => true,
			],
			'login' => [
				'label' => 'Login',
				'href' => 'index.php?app=login',
				'visible' => true,
			],
			'register' => [
				'label' => 'Register',
				'href' => 'index.php?app=register',
				'visible' => true,
			]
		];
	}
}