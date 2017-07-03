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

class Random extends ModelHeader {

    private $_article;

    public function __construct() {
        $this->loadLocale();
        $this->generate();
    }

    private function generate() : void {
        $query = QueryFactory::produce('select', '
            SELECT title
                FROM {db_prefix}article
                WHERE `status` = :article_status
                AND locale_id = :locale
                ORDER BY RAND()
                LIMIT 1
        ');

        $query->setParams([
            'article_status' => 1,
            'locale' => $this->locale->getID()
        ]);

        $result = $query->execute();

        foreach ($result as $article) {
            $this->_article = Sanitizer::addUnderscores($article['title']);
            return;
        }

        $this->_article = null;
    }

    public function getArticle() : string {
        return $this->_article;
    }
}