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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\AppHeader;
use DraiWiki\src\main\models\Article as Model;

class Article extends AppHeader {

	private $_model, $_route;

	public function __construct(?string $title, bool $isHomepage = false) {
		$this->_model = new Model($title, $isHomepage);
		$this->_route = Registry::get('route');

		$this->_model->setIsEditing($this->areWeEditing());
	}

    /**
     * If we are editing, we need to tell so to the model. If we don't the model will
     * get mad and divorce us. Not good! :(
     * @return bool This tells us if we're editing. Yep, really!
     */
	private function areWeEditing() : bool {
	    return !empty($this->_route->getParams()) && $this->_route->getParams()['action'] == 'edit';
    }
}