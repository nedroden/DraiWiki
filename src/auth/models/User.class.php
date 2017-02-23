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
 * This class adds a session to the database
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;

class User {

	private $_isGuest, $_userInfo;

	private static $_instance;

	private function __construct() {
		$this->_isGuest = empty($_SESSION['user']);
		$this->load();
	}

	public static function instantiate() {
		if (self::$_instance == null)
			self::$_instance = new self();

		return self::$_instance;
	}

	public function get() {
		return $this->_userInfo;
	}

	private function load() {
		if (!$this->_isGuest) {
			$query = new Query('
				SELECT ID, first_name, last_name, email, birthdate, registration_date,
						locale, groups, preferences, edits, ip_address, activated
					FROM {db_prefix}users u
					WHERE ID = :ID
					AND activated = :activated
					LIMIT 1
			');

			$query->setParams([
				'ID' => $_SESSION['user']['ID'],
				'activated' => 1
			]);
			$result = $query->execute();

			foreach ($result as $infoResult) {
				$userInfo = $infoResult;
			}
		}

		if (empty($userInfo)) {
			$userInfo = [
				'ID' => 0,
				'first_name' => 'guest',
				'last_name' => null,
				'email' => null,
				'birthdate' => null,
				'registration_date' => null,
				'locale' => 'default',
				'groups' => [5],
				'preferences' => [],
				'edits' => 0,
				'ip_address' => $_SERVER['REMOTE_ADDR'],
				'activated' => 1
			];
		}

		$this->_userInfo = $userInfo;
	}
}