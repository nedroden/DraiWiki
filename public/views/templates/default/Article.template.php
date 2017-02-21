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

use DraiWiki\views\Template;

class Article extends Template {

	private $_imageUrl, $_skinUrl;

	public function __construct($imageUrl, $skinUrl) {
		$this->_imageUrl = $imageUrl;
		$this->_skinUrl = $skinUrl;
	}

	public function showHeader() {

	}

	public function showContent() {
		echo '
			<h1 class="articleTitle">', $this->data['title'], '</h1>
			<hr />
			', $this->data['body'];
	}

	public function showFooter() {

	}
}