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

class Registration extends Template {

	public function __construct() {
		$this->loadLocale();
	}

	public function showContent() {
		echo '
				<div id="registrationPage">
					<form action="', $this->data['action'],'" method="post">
						<div class="messageBox info">
							', $this->locale->read('registration', 'this_is_the_registration_page'), '
						</div>';

			if (!empty($this->data['errors']))
				$this->showErrors();

			echo '
						<label 	for="first_name"', 
								(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
								$this->locale->read('registration', 'first_name'), '
						</label>
						<input 	type="text"
								name="first_name"
								placeholder="', $this->locale->read('registration', 'placeholder_first_name'), '"
								maxlength="', Main::$config->read('user', 'MAX_FIRST_NAME_LENGTH'), '" /><br />

						<label 	for="last_name"', 
								(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
								$this->locale->read('registration', 'last_name'), '
						</label>
						<input 	type="text" 
								name="last_name" 
								placeholder="', $this->locale->read('registration', 'placeholder_last_name'), '"
								maxlength="', Main::$config->read('user', 'MAX_LAST_NAME_LENGTH'), '" /><br />

						<label 	for="password"', 
								(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
								$this->locale->read('registration', 'password'), '
						</label>
						<input 	type="password"
								name="password"
								placeholder="', $this->locale->read('registration', 'placeholder_password'), '"
								maxlength="', Main::$config->read('user', 'MAX_PASSWORD_LENGTH'), '" /><br />

						<label 	for="confirm_password"', 
								(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
								$this->locale->read('registration', 'confirm_password'), '
						</label>
						<input 	type="password"
								name="confirm_password"
								maxlength="', Main::$config->read('user', 'MAX_PASSWORD_LENGTH'), '" /><br />

						<label 	for="email"', 
								(array_key_exists('password', $this->data['errors']) ? ' class="containsError"' : ''), '>',
								$this->locale->read('registration', 'email'), '
						</label>
						<input 	type="text"
								name="email"
								placeholder="', $this->locale->read('registration', 'placeholder_email'), '"
								maxlength="', Main::$config->read('user', 'MAX_EMAIL_LENGTH'), '" /><br />

						<div id="agreement">
							', $this->data['agreement'], '<br />
							<div id="acceptAgreement">
								<input type="checkbox" name="agreement_accept" /> ', $this->locale->read('registration', 'i_accept'), '
							</div>
						</div>

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