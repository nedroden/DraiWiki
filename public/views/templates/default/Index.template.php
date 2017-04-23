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

class Index extends Template {

	private $_imageUrl, $_skinUrl, $_menuItems = [], $_sideMenuItems = [];

	public function __construct($imageUrl, $skinUrl) {
		$this->_imageUrl = $imageUrl;
		$this->_skinUrl = $skinUrl;
		$this->loadLocale();
	}

	public function showHeader() {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>';

	if (empty($this->data['title']))
		echo Main::$config->read('wiki', 'WIKI_NAME'), ' | ', Main::$config->read('wiki', 'WIKI_SLOGAN');

	else
		echo $this->data['title'], ' | ', Main::$config->read('wiki', 'WIKI_NAME');

	echo '</title>
		<link rel="stylesheet" type="text/css" href="', $this->_skinUrl, '" />
		<script type="text/javascript" src="' . Main::$config->read('path', 'BASE_URL'). 'src/javascript/Main.js"></script>

        <link rel="icon" href="', Main::$config->read('path', 'BASE_URL'), '/favicon.png" sizes="16x16" type="image/png">';

	foreach ($this->stylesheets as $stylesheet) {
		echo '
		<link rel="stylesheet" type="text/css" href="', $this->getStylesheet($stylesheet),'" />';
	}

	if (!empty($this->data['header']))
		echo $this->data['header'];

	echo '
	</head>
	<body>
		<div id="wrapper">
			<div id="header_section">
				<div id="topbar">';

			$this->showMenu();

			echo '
					<div id="userinfo">
						', sprintf($this->locale->read('index', 'hello'), $this->user['first_name']), '
					</div>
					<br class="clear" />
				</div>
				<div id="header">
					<a href="', Main::$config->read('path', 'BASE_URL'), 'index.php">', Main::$config->read('wiki', 'WIKI_NAME'), '</a>
				</div>';

			if (!empty($this->data['title']))
				echo '
				<div id="contentHeader">
					', $this->data['title'], '
				</div>';

		echo '
			</div>
			<div id="content">';

		$this->showSidebar();

		echo '
				<div class="col80">';
	}

	public function showFooter() {
echo '
				</div>
				<br class="clear" />
			</div>
			<div id="copyright">
				<div class="col33">', $this->getCopyright(), '</div>
				<div class="col33 align_center"><a href="#topbar">', $this->locale->read('index', 'to_top'), '</a></div>
				<div class="col33 align_right">
					<strong>', $this->locale->read('index', 'locale'), '</strong> ', $this->locale->getLanguage()['native'], '
				</div>
				<br class="clear" />
			</div>
		</div>
	</body>
</html>';
	}

	private function showMenu() {
		echo '
				<div id="menu">';

		foreach ($this->_menuItems as $item) {
			if ($item['visible'])
				echo '
					<a href="', $item['href'], '">', $this->locale->read('index', $item['label']), '</a>';
		}

		echo '
				</div>';
	}

	private function showSidebar() {
		echo '
			<div id="sidebar" class="col20">';

		foreach ($this->_sideMenuItems as $section) {
			if (!$section['visible'])
				continue;

			echo '
				<div class="sidebar_header">', $this->locale->read('index', $section['label']), '</div>
				<ul>';

			foreach ($section['items'] as $item) {
				if ($item['visible']) {
					echo '
						<li><a href="', $item['href'], '">';

					if (!empty($item['hardcoded']) && $item['hardcoded'])
						echo $item['label'];
					else
						echo $this->locale->read('index', $item['label']);

					echo '</a></li>';
				}
			}

			echo '
				</ul>';
		}

		echo '
			</div>';
	}

	public function pushMenu($menuItems) {
		$this->_menuItems = $menuItems;
	}

	public function pushSidebarMenu($menuItems) {
		if (empty($this->_sideMenuItems))
			$this->_sideMenuItems = $menuItems;

		else
			$this->_sideMenuItems = array_merge($this->_sideMenuItems, $menuItems);
	}
}
