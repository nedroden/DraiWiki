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
 * This class validates the user's input in the registration page form.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \DraiWiki\src\main\controllers\Main;

require_once Main::$config->read('path', 'BASE_PATH') . 'src/auth/models/AuthTool.class.php';

class Registration extends ModelController {

	public function __construct() {
		$this->loadLocale();
		$this->locale->loadFile('registration');
	}

	public function getAction() {
		return Main::$config->read('path', 'BASE_URL') . 'index.php/register';
	}

	public function getTitle() {
		return $this->locale->read('registration', 'page_title');
	}

	public function verify() {
		$fields = [
			'first_name' => [
				'name' => 'first_name',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_FIRST_NAME_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_FIRST_NAME_LENGTH')
			],
			'last_name' => [
				'name' => 'last_name',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_LAST_NAME_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_LAST_NAME_LENGTH')
			],
			'password' => [
				'name' => 'password',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_PASSWORD_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_PASSWORD_LENGTH'),
				'specialformat' => 'password',
				'mustmatch' => 'confirm_password'
			],
			'confirm_password' => [
				'name' => 'confirm_password',
				'required' => true,
				'duplicate' => true
			],
			'email' => [
				'name' => 'email',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_EMAIL_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_EMAIL_LENGTH'),
				'specialformat' => 'email'
			]
		];

		$errors = [];
		$correctFields = [];

		foreach ($fields as $field) {
			if (empty($_POST[$field['name']]) && $field['required']) {
				$errors[$field['name']] = $this->locale->read('registration', 'field_empty_' . $field['name']);
				continue;
			}

			$_POST[$field['name']] = trim($_POST[$field['name']]);

			if (!empty($field['duplicate']) && $field['duplicate'])
				continue;

			if (strlen($_POST[$field['name']]) < $field['minlength']) {
				$errors[$field['name']] = str_replace('{length}', $field['minlength'], $this->locale->read('registration', 'too_short_' . $field['name']));
				continue;
			}

			if (strlen($_POST[$field['name']]) > $field['maxlength']) {
				$errors[$field['name']] = str_replace('{length}', $field['maxlength'], $this->locale->read('registration', 'too_long_' . $field['name']));
				continue;
			}

			if (!empty($field['mustmatch']) && $_POST[$field['name']] != $_POST[$field['mustmatch']]) {
				$errors[$field['mustmatch']] = $this->locale->read('registration', 'passwords_do_not_match');
				continue;
			}

			if (!empty($field['specialformat']) && !$this->hasFormat($_POST[$field['name']], $field['specialformat'])) {
				$errors[$field['name']] = $this->locale->read('registration', 'incorrect_format_' . $field['name']);
				continue;
			}

			// Thanks to Stema from Stack Overflow: http://stackoverflow.com/users/626273/stema
			if (!preg_match('/^[\p{L}\p{N} .-]+$/', $_POST[$field['name']]) && !in_array($field['specialformat'], ['password', 'email'])) {
				$errors[$field['name']] = $this->locale->read('registration', 'invalid_chars');
				continue;
			}

			$correctFields[] = $field['name'];
		}

		if (empty($_POST['agreement_accept'])) {
			$errors['agreement_accept'] = $this->locale->read('registration', 'agreement_not_accepted');
		}
		else if (empty($errors)) {
			$query = new Query('
				SELECT email
					FROM {db_prefix}users
					WHERE email = :email
					LIMIT 1
			');

			$query->setParams(['email' => $_POST['email']]);
			$result = $query->execute();

			if (!empty($result))
				$errors['email'] = $this->locale->read('registration', 'email_in_use');
		}

		if (!empty($errors))
			return ['errors' => $errors, 'correct' => $correctFields];
		else
			return [];
	}

	public function addToDatabase() {
		$query = new Query('
			INSERT
				INTO {db_prefix}users (
					first_name, last_name, email, password, registration_date,
					locale, groups, ip_address, activated
				)
				VALUES (
					:first_name,
					:last_name,
					:email,
					:password,
					STR_TO_DATE(:registration_date, \'%m-%d-%Y\'),
					:locale,
					:groups,
					:ip_address,
					:activated
				)
		');

		$query->setParams([
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'email' => $_POST['email'],
			'password' => AuthTool::hash($_POST['password']),
			'registration_date' => date("m-d-Y"),
			'locale' => $this->locale->getLanguage()['code'],
			'groups' => 2,
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'activated' => 1
		]);

		$query->execute('update');
	}

	private function hasFormat($value, $type) {
		switch ($type) {
			case 'password':
				// Note: passwords do not need to be checked for HTML, since they are NEVER displayed
				return true;
			case 'email':
				return filter_var($value, FILTER_VALIDATE_EMAIL);
			default:
				return false;
		}
	}
}
