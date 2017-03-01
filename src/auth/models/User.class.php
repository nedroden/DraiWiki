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
use \DraiWiki\src\main\models\Locale;

class User {

	private $_isGuest, $_userInfo, $_permissions, $_locale;

	private static $_instance;

	private function __construct() {
		$this->_isGuest = empty($_SESSION['user']);
		$this->_locale = Locale::instantiate();
		$this->_permissions = [];
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

	public function isGuest() {
		return $this->_isGuest;
	}

	public function hasPermission($permission) {
		return in_array($permission, $this->_permissions);
	}

	private function load() {
		if (!$this->_isGuest) {
			$query = new Query('
				SELECT ID, first_name, last_name, email, birthdate, registration_date,
						locale, groups, preferences, edits, ip_address, activated
					FROM {db_prefix}users
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

			$userInfo['groups'] = explode(',', $userInfo['groups']);
		}

		if (empty($userInfo)) {
			$userInfo = [
				'ID' => 0,
				'first_name' => 'guest',
				'last_name' => null,
				'email' => null,
				'birthdate' => null,
				'registration_date' => null,
				'locale' => $this->_locale->getLanguage()['homepage'],
				'groups' => [5],
				'preferences' => [],
				'edits' => 0,
				'ip_address' => $_SERVER['REMOTE_ADDR'],
				'activated' => 1
			];
		}

		$this->_userInfo = $userInfo;
		$this->loadGroups();
	}

	private function loadGroups() {
		$groupCount = count($this->_userInfo['groups']);
		$groupPlaceholders = '';

		foreach ($this->_userInfo['groups'] as $key => $group) {
			$groupPlaceholders .= "'" . $group . "'" . ($key < ($groupCount - 1) ? ', ' : '');
		}

		$query = new Query('
			SELECT ID, permission_profile, `name`, color, dominant
				FROM {db_prefix}user_groups g
				WHERE ID IN (' . $groupPlaceholders . ')
		');

		$result = $query->execute();

		/**
		 * Whether or not a group is dominant only affects the permission profile. Since
		 * that's the only difference between a normal group and a dominant group, we can
		 * safely return all groups.
		 */
		$dominantGroups = [];
		$normalGroups = [];

		foreach ($result as $group) {
			$groupInfo = [
				'ID' => $group['ID'],
				'color' => $group['color'],
				'dominant' => $group['dominant'],
				'name' => $group['name']
			];

			if ($group['dominant'] == 1)
				$dominantGroups[] = $group;
			else
				$normalGroups[] = $group;
		}

		$this->loadPermissions(empty($dominantGroups) ? $normalGroups : $dominantGroups);		
	}

	private function loadPermissions($groups) {
		$groupCount = count($groups);
		$groupPlaceholders = '';

		foreach ($groups as $key => $group) {
			$groupPlaceholders .= "'" . $group['ID'] . "'" . ($key < ($groupCount - 1) ? ', ' : '');
		}

		$query = new Query('
			SELECT ID, permissions
				FROM {db_prefix}permission_profiles
				WHERE ID IN (' . $groupPlaceholders . ')
		');

		$result = $query->execute();

		foreach ($result as $profile) {
			$permissions = explode(';', $profile['permissions']);
			foreach ($permissions as $permission) {
				$this->_permissions[] = $permission;
			}
		}
	}
}