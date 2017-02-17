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

class Error extends Template {

	private $_imageUrl, $_skinUrl;

	public function __construct($imageUrl, $skinUrl) {
		$this->_imageUrl = $imageUrl;
		$this->_skinUrl = $skinUrl;
	}

	/**
	 * This method displays the page header HTML. Since we're dealing with an
	 * entirely new page here, it includes the HTML opening tags (for the lack
	 * of a better word).
	 * @return 	void
	 */
	public function showHeader() {
		echo '<!DOCTYPE HTML>
<html>
	<head>
		<title>', $this->data['title'], '</title>
		<link rel="stylesheet" type="text/css" href="', $this->_skinUrl, '" />
	</head>
	<body>
		<div id="wrapper">';
	}

	/**
	 * The controller made sure the model provided us with the necessary data
	 * regarding the error. This is the method that shows it.
	 * @return 	void
	 */
	public function showBody() {
		echo '
			<h1>', $this->data['title'], '</h1>
			<p>', $this->data['body'], '</p>';

		if (!empty($this->data['detailed']))
			echo '
				<p>', $this->data['detailed'], '</p>';
	}

	/**
	 * As its name suggests, this method displays the page footer.
	 * @return 	void
	 */
	public function showFooter() {
		echo '
		</div>
	</body>
</html>';
	}
}