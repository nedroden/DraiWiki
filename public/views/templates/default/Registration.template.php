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
					<div class="info">
						', $this->locale->read('registration', 'this_is_the_registration_page'), '
					</div>
					<label for="first_name">', $this->locale->read('registration', 'first_name'), '</label>
					<input type="text" name="first_name" placeholder="', $this->locale->read('registration', 'placeholder_first_name'), '" /><br />

					<label for="last_name">', $this->locale->read('registration', 'last_name'), '</label>
					<input type="text" name="last_name" placeholder="', $this->locale->read('registration', 'placeholder_last_name'), '" /><br />

					<label for="email">', $this->locale->read('registration', 'email'), '</label>
					<input type="text" name="email" placeholder="', $this->locale->read('registration', 'placeholder_email'), '" /><br />

					<div id="agreement">
						', $this->data['agreement'], '
					</div>

					<input type="submit" value="', $this->locale->read('index', 'submit'), '" />
				</form>
			</div>';
	}
}