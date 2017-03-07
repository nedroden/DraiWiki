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

use \DraiWiki\src\auth\controllers\Permission;
use \DraiWiki\src\auth\models\User;

class Menu {

	private $_items = [], $_user;

	public function __construct() {
		$this->_user = User::instantiate();
		$this->set();
	}

	public function get() {
		return $this->_items;
	}

	private function set() {
		// Note: the label should refer to a string in Index.language.php. The correct string is then loaded automatically.
		$this->_items = [
			'home' => [
				'label' => 'home',
				'href' => 'index.php',
				'visible' => true
			],
			'admin' => [
				'label' => 'admin',
				'href' => 'admin/index.php',
				'visible' => Permission::checkAndReturn('access_admin')
			],
			'login' => [
				'label' => 'login',
				'href' => 'index.php?app=login',
				'visible' => $this->_user->isGuest()
			],
			'register' => [
				'label' => 'register',
				'href' => 'index.php?app=register',
				'visible' => $this->_user->isGuest()
			],
			'logout' => [
				'label' => 'logout',
				'href' => 'index.php?app=logout',
				'visible' => !$this->_user->isGuest()
			]
		];
	}
}