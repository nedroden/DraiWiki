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
 * This class is used for loading pages.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\database\controllers\ModelController;
use DraiWiki\src\database\controllers\Query;

class Article extends ModelController {

	public function __construct() {
		parent::__construct();
	}

	public function retrieve($id) {
		// Note: this is just to test the Query class, this will be removed as soon as it works properly.
		$query = new Query();
		echo $query->retrieve('ID', 'username', 'first_name')->from('test')->toString();
	}
}