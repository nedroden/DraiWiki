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
 * This class ensures that is able to include external CSS files.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\views;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\Locale;

abstract class Template {

	protected $locale, $data;

	protected function getStylesheet($name) {
		return Main::$config->read('path', 'BASE_URL') . 'index.php?stylesheet=' . lcfirst($name);
	}

	protected function loadLocale() {
		$this->locale = Locale::instantiate();
	}

	protected function getCopyright() {
		return 'Powered by <a href="http://robertmonden.com/draiwiki" target="_blank">DraiWiki</a> ' . DraiWikiVersion . ' | 
			&copy; 2017 Robert Monden';
	}

	public function setData($data = []) {
		if (!is_array($data))
			return false;
		else
			$this->data = $data;
	}
}