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
 * This class is used for loading the other required classes and setting up the wiki.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\interfaces\App;
use DraiWiki\views\View;

class Home implements App{

	private $_view, $_template;

	public function __construct() {
		$this->_view = new View('Home');
		$this->_template = $this->_view->get();
	}

	public function show() {

	}

	public function getTitle() {

	}
}