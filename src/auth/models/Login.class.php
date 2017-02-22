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

class Login extends ModelController {

	public function __construct() {
		$this->loadLocale();
		$this->locale->loadFile('login');
	}

	public function getAction() {
		return Main::$config->read('path', 'BASE_URL') . 'index.php?app=login';
	}

	public function getTitle() {
		return $this->locale->read('login', 'page_title');
	}

	public function validate() {
		$fields = [
			'email' => [
				'name' => 'email',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_EMAIL_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_EMAIL_LENGTH')
			],
			'password' => [
				'name' => 'password',
				'required' => true,
				'minlength' => Main::$config->read('user', 'MIN_PASSWORD_LENGTH'),
				'maxlength' => Main::$config->read('user', 'MAX_PASSWORD_LENGTH')
			]
		];

		$errors = [];
		$correct = [];
		foreach ($fields as $field) {
			if (empty($_POST[$field['name']]) && $field['required']) {
				$errors[$field['name']] = $this->locale->read('login', 'field_is_empty_' . $field['name']);
				continue;
			}

			else if (strlen($_POST[$field['name']]) < $field['minlength']) {
				$errors[$field['name']] = str_replace('{length}', $field['minlength'], $this->locale->read('login', 'input_too_short_' . $field['name']));
				continue;
			}

			else if (strlen($_POST[$field['name']]) > $field['maxlength']) {
				$errors[$field['name']] = str_replace('{length}', $field['maxlength'], $this->locale->read('login', 'input_too_long_' . $field['name']));
				continue;
			}

			if ($field['name'] != 'password')
				$correct[] = $field['name'];
		}

		if (empty($errors)) {
			$query = new Query('
				SELECT ID, email, password
					FROM {db_prefix}users
					WHERE email = :email
					AND password = :password
			');

			$query->setParams([
				'email' => $_POST['email'],
				'password' => AuthTool::hash($_POST['password'])
			]);
			$result = $query->execute();

			if (empty($result)) 
				$errors['email'] = $this->locale->read('login', 'no_match_found');
			else {
				foreach ($result as $user) {
					return $user['ID'];
				}
			}
		}

		return ['errors' => $errors, 'correct' => $correct];
	}
}