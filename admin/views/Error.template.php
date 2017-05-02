<?php
/**
 * STORM
 * DraiWiki admin default theme
 *
 * @author		Robert Monden
 * @copyright	DraiWiki development team, 2017
 * @version		1.0 Alpha 1
 */

namespace DraiWiki\admin\views;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\admin\models\Template;

class Error extends Template {

	private $_imageUrl, $_skinUrl;

	public function __construct($imageUrl, $skinUrl) {
		$this->_imageUrl = $imageUrl;
		$this->_skinUrl = $skinUrl;
        $this->loadLocale();
	}

	public function show() {
        echo '<!DOCTYPE HTML>
<html>
    <head>
        <title>', $this->data['title'], '</title>
    </head>

    <body>
        <h1>', $this->data['title'], '</h1>
        <p>', $this->data['message'], '</p>
    </body>
</html>';
	}
}
