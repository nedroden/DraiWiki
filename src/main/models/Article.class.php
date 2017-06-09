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

use DraiWiki\src\core\controllers\SelectQuery;
use DraiWiki\src\core\models\Sanitizer;

class Article extends ModelHeader {

	private $_requestedArticle, $_isHomepage;

	private $_title, $_body;

	private $_forceEdit, $_isEditing;

	public function __construct(?string $requestedArticle, bool $isHomepage) {
		$this->_requestedArticle = $requestedArticle;
		$this->_isHomepage = $isHomepage;

		$this->load();
	}

	private function load() : void {
		$query = new SelectQuery('
			SELECT a.title, a.locale_id, a.status, h.body
				FROM {db_prefix}article a
				INNER JOIN {db_prefix}article_history h ON (a.id = h.article_id)
				WHERE ' . ($this->_isHomepage ? 'a.id' : 'a.title') . ' = :article
				ORDER BY h.updated DESC
				LIMIT 1
		');

		$query->setParams([
			'article' => $this->_isHomepage ? $this->getHomepageId() : Sanitizer::ditchUnderscores($this->_requestedArticle)
		]);

		$result = $query->execute();

		if (count($result) == 0)
		    $_forceEdit = true;
	}

	public function setIsEditing(bool $status) : void {
	    $this->_isEditing = $status;
    }

	private function getHomepageId() : int {
		return 1;
	}
}