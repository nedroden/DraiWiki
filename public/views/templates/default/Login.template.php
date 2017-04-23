<?php
/**
 * HURRICANE
 * DraiWiki default theme
 *
 * @author		Robert Monden
 * @copyright	DraiWiki development team, 2017
 * @version		1.0 Alpha 1
 */

namespace DraiWiki\views\templates;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\views\Template;

class Login extends Template {

	public function __construct() {
		$this->loadLocale();
	}

	public function showContent() {
		echo '
			<div id="registrationPage">
				<form action="', $this->data['action'],'" method="post">
                    <h1 class="title_with_margin">', $this->locale->read('login', 'page_title'), '</h1>';

		if (!empty($this->data['errors']))
			$this->showErrors();

		echo '
					<label 	for="email"',
							(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
							$this->locale->read('login', 'email'), '
					</label>
					<input 	type="text"
							name="email"
							placeholder="', $this->locale->read('login', 'placeholder_email'), '"
							maxlength="', Main::$config->read('user', 'MAX_EMAIL_LENGTH'), '" /><br />

					<label 	for="password"',
							(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
							$this->locale->read('login', 'password'), '
					</label>
					<input 	type="password"
							name="password"
							placeholder="', $this->locale->read('login', 'placeholder_password'), '"
							maxlength="', Main::$config->read('user', 'MAX_PASSWORD_LENGTH'), '" /><br />

					<input type="submit" value="', $this->locale->read('index', 'submit'), '" />
				</form>
			</div>';
	}

	private function showErrors() {
		echo '
					<div class="messageBox error">
						<ul>';

				foreach ($this->data['errors'] as $error)
					echo '
							<li>', $error, '</li>';

				echo '
						</ul>
					</div>';
	}
}
