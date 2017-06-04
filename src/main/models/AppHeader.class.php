<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

abstract class AppHeader {

    protected $config, $title;

	/**
	 * Wether or not main templates should be shown. There are four possible values:
	 * 	neither -> Show both the header and the footer
	 * 	upper	-> Hide the header, but display the footer
	 * 	lower	-> Display the header, but hide the footer
	 * 	both	-> Hide both the header and the footer
	 *
	 * @var string $ignoreTemplates Determines which template parts will be shown
	 */
	protected $ignoreTemplates = 'neither';

    protected function loadConfig() {
        $this->config = Registry::get('config');
    }

	public function getIgnoreTemplates() {
		return $this->ignoreTemplates;
	}

	protected function redirectTo($url) {
		header('Location: ' . $url);
		die;
	}

	public function execute() {
		return;
	}

	public function display() {
		return;
	}

	public function getAppInfo() {
		$context = [
			'title' => $this->title,
			'permissions' => []
		];

		return $context;
	}
}
