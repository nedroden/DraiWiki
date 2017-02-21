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
 * This class loads the user agreement.
 * @since 		1.0 Alpha 1
 * @author 		DraiWiki development team
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use \DraiWiki\src\database\controllers\ModelController;
use \DraiWiki\src\database\controllers\Query;
use \Parsedown;

class Agreement extends ModelController {

	private $_locale, $_parsedown;

	public function __construct() {
		$this->loadLocale();
		$this->_locale = $this->locale->getLanguage()['code'];
		$this->_parsedown = new Parsedown();
	}

	public function retrieve() {
		$query = new Query('
			SELECT ID, body, locale
				FROM {db_prefix}agreements
				WHERE locale = :locale
				ORDER BY ID DESC
				LIMIT 1
		');

		$query->setParams(['locale' => $this->_locale]);
		$result = $query->execute();

		foreach ($result as $agreement) {
			return $this->_parsedown->text($agreement['body']);
		}

		// If we're still here, that means no agreement has been found. Let's try and get the English one.
		if ($this->_locale != 'en_US') {
			$query = new Query('
				SELECT ID, body, locale
					FROM {db_prefix}agreements
					WHERE locale = `en_US`
					ORDER BY ID DESC
					LIMIT 1
			');

			$result = $query->execute();

			foreach ($result as $agreement) {
				return $this->_parsedown->text($agreement['body']);
			}
		}

		return false;
	}
}