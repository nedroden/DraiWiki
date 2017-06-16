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

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\errors\FatalError;
use Parsedown;

class Article extends ModelHeader {

	private $_requestedArticle, $_isHomepage;

	private $_title, $_body, $_bodyUnparsed, $_bodySafeHTML, $_details;

	private $_forceEdit, $_isEditing;

	private $_parsedown;

	public function __construct(?string $requestedArticle, bool $isHomepage) {
		$this->_requestedArticle = $requestedArticle;
		$this->_isHomepage = $isHomepage;
        $this->_parsedown = new Parsedown();

		$this->loadLocale();
		$this->locale->loadFile('article');
		$this->load();
	}

	private function load() : void {
		$query = QueryFactory::produce('select', '
			SELECT a.title, a.locale_id, a.status, h.body
				FROM {db_prefix}article a
				INNER JOIN {db_prefix}article_history h ON (a.id = h.article_id)
				WHERE ' . ($this->_isHomepage ? 'a.id' : 'a.title') . ' = :article
				AND STATUS = 1
				ORDER BY h.updated DESC
				LIMIT 1
		');

		$query->setParams([
			'article' => $this->_isHomepage ? $this->getHomepageId() : Sanitizer::ditchUnderscores($this->_requestedArticle)
		]);

		$result = $query->execute();

		if (count($result) == 0) {
            $this->_forceEdit = true;

            // Because we haven't implemented editing functionality yet
            $this->_isHomepage = true;
            $this->load();
            $this->_bodySafeHTML .= '<br /><em>:: Homepage loaded for development reasons</em>';
            return;
        }

		foreach ($result as $article)
		    $this->setArticleInfo($article);
	}

	private function getHomepageId() : int {
	    $query = QueryFactory::produce('select', '
	        SELECT h.article_id
	            FROM {db_prefix}homepage h
	            INNER JOIN {db_prefix}locale l ON (l.code = :locale_code)
	            WHERE h.locale_id = l.id
	            LIMIT 1
	    ');

	    $query->setParams([
	        'locale_code' => $this->locale->getCode()
        ]);

	    $result = $query->execute();
	    $homepage = 0;

        foreach ($result as $article) {
            if (!is_numeric($article['article_id']))
                (new FatalError($this->locale->read('error', 'homepage_id_not_a_number')))->trigger();

            $homepage = $article['article_id'];
        }

        if (count($result) == 0 || $homepage == 0)
            (new FatalError($this->locale->read('error', 'no_homepage_found')))->trigger();

		return $homepage;
	}

	private function setArticleInfo(array $info) : void {
	    $this->_title = $info['title'] ?? $this->locale->read('article', 'new_article');

	    $this->_body = $this->_parsedown->text($info['body']) ?? '';
	    $this->_bodyUnparsed = $info['body'] ?? '';
	    $this->_bodySafeHTML = $this->_parsedown->setMarkupEscaped(true)->text($info['body']) ?? '';

	    // Status IDs should be integers. Always.
	    $status = !empty($info['status']) ? (int) $info['status'] : 0;

	    $this->_details = [
            'status' => $status
        ];
    }

    public function getTitle() : string {
        /* Ok, this is a little confusing, but there are two properties
           called "title". One of them, "_title" refers to the article
           title, while the other, "title" refers to the page title. */
        if ($this->_isEditing)
            return $this->locale->read('article', 'editing') . $this->_title;
        else
            return $this->_title;
    }

    public function setIsEditing(bool $status) : void {
        $this->_isEditing = $status;
    }

    public function prepareData() : array {
	    return [
	        'title' => $this->_title,
            'body' => $this->_body,
            'body_unparsed' => $this->_bodyUnparsed,
            'body_safe' => $this->_bodySafeHTML
        ];
    }
}