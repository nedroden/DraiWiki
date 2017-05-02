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

namespace DraiWiki\admin\models;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\admin\controllers\ModelController;
use DraiWiki\src\core\controllers\Registry;

/**
 * This class loads the current user information. It also determines whether
 * or not the user is logged in. This class also loads the user's permissions.
 *
 * @since		1.0 Alpha 1
 */
class User extends ModelController {

    private $_isGuest;

    public function __construct() {
        $this->_isGuest = $this->isGuest();

        $this->load();
    }

    private function load() {

    }

    private function isGuest() {
		return true;
    }
}
