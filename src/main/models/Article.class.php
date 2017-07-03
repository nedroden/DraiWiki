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
use DraiWiki\src\core\models\{InputValidator, PostRequest, Sanitizer};
use Parsedown;

class Article extends ModelHeader {

    private $_requestedArticle, $_isHomepage;

    private $_id, $_title, $_titleSafe, $_details;

    /**
     * @var string $_body Parsed article body with HTML not escaped
     * @var string $_bodyUnparsed Raw article body
     * @var string $_bodySafeHTML Parsed article body with HTML escaped
     */
    private $_body, $_bodyUnparsed, $_bodySafeHTML;

    private $_forceEdit, $_isEditing;
    private $_parsedown;
    private $_tempInfo;
    private $_updatedTitle = false;

    public function __construct(?string $requestedArticle, bool $isHomepage) {
        $this->_requestedArticle = $requestedArticle;
        $this->_isHomepage = $isHomepage;
        $this->_parsedown = new Parsedown();

        $this->loadLocale();
        $this->loadConfig();
        $this->locale->loadFile('article');
        $this->locale->loadFile('editor');
        $this->load();
    }

    private function load() : void {
        if (strlen($this->_requestedArticle) > $this->config->read('max_title_length'))
            $this->_requestedArticle = $this->locale->read('article', 'new_article');

        $query = QueryFactory::produce('select', '
            SELECT a.id, a.title, a.locale_id, a.status, h.body
                FROM {db_prefix}article a
                INNER JOIN {db_prefix}article_history h ON (a.id = h.article_id)
                WHERE ' . ($this->_isHomepage ? 'a.id' : 'a.title') . ' = :article
                AND STATUS = 1
                ORDER BY h.updated DESC
                LIMIT 1
        ');

        $query->setParams([
            'article' => $this->_isHomepage ? $this->locale->getHomepageID() : Sanitizer::ditchUnderscores($this->_requestedArticle)
        ]);

        $result = $query->execute();

        if (count($result) == 0) {
            $this->_forceEdit = true;
            $this->setArticleInfo([]);
        }

        foreach ($result as $article)
            $this->setArticleInfo($article);
    }

    private function setArticleInfo(array $info) : void {
        $this->_id = $info['id'] ?? 0;
        $this->_title = $info['title'] ?? Sanitizer::escapehtml($this->_requestedArticle) ?? $this->locale->read('article', 'new_article');
        $this->_titleSafe = Sanitizer::addUnderscores($this->_title);

        $this->_body = $this->_parsedown->text($info['body'] ?? '');
        $this->_bodyUnparsed = $info['body'] ?? '';
        $this->_bodySafeHTML = $this->_parsedown->setMarkupEscaped(true)->text($info['body'] ?? '');

        // Status IDs should be integers. Always.
        $status = !empty($info['status']) ? (int) $info['status'] : 0;

        $this->_details = [
            'status' => $status
        ];
    }

    private function getAdditionalData() : array {
        $data = [];

        if ($this->_isEditing || $this->_forceEdit) {
            $data['action'] = $this->config->read('url') . '/index.php/article/' . $this->_titleSafe . '/edit';
        }

        return $data;
    }

    private function existsById(int $id) : bool {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM {db_prefix}article
                WHERE id = :id
                LIMIT 1
        ');

        $query->setParams([
            'id' => $id
        ]);

        foreach ($query->execute() as $entry)
            return true;

        return false;
    }

    private function existsByName(string $title, int $id) : bool {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM {db_prefix}article
                WHERE title = :title
                AND id != :id
                LIMIT 1
        ');

        $query->setParams([
            'id' => $id,
            'title' => $title
        ]);

        foreach ($query->execute() as $entry)
            return true;

        return false;
    }

    public function getTitle() : string {
        /* Ok, this is a little confusing, but there are two properties
           called "title". One of them, "_title" refers to the article
           title, while the other, "title" refers to the page title. */
        return !$this->_isEditing && !$this->_forceEdit ? $this->_title : $this->locale->read('article', 'editing') . $this->_title;
    }

    public function setIsEditing(bool $status) : void {
        $this->_isEditing = $status;
    }

    public function prepareData() : array {
        $additionalData = $this->getAdditionalData();

        return [
            'id' => $this->_id,
            'title' => ucfirst($this->_title),
            'title_real' => $this->_title,
            'body' => $this->_body,
            'body_unparsed' => $this->_bodyUnparsed,
            'body_safe' => $this->_bodySafeHTML
        ] + $additionalData;
    }

    public function handlePostRequest() : void {
        if (empty($_POST))
            return;

        foreach (['id', 'title', 'body'] as $field) {
            $this->_tempInfo[$field] = [
                'request' => new PostRequest($_POST[$field] ?? ''),
                'validator' => new InputValidator($_POST[$field] ?? ''),
                'value' => $_POST[$field] ?? ''
            ];
        }

        $this->_tempInfo['title']['request']->trim();
    }

    public function validate(array &$errors) : void {
        $id = $this->_tempInfo['id'];
        $title = $this->_tempInfo['title'];
        $body = $this->_tempInfo['body'];

        // Make sure we have a valid id
        if (!is_numeric($id['value']) || $id['validator']->aboveIntLimit()) {
            $errors['id'] = $this->locale->read('editor', 'invalid_id');
            return;
        }

        $id['value'] = (int) $id['value'];

        if ($id['value'] != 0 && !$this->existsById($id['value'])) {
            $errors['id'] = $this->locale->read('editor', 'article_not_found');
            return;
        }

        // Check the length of the title
        if ($title['validator']->isTooShort($minLength = (int) $this->config->read('min_title_length')))
            $errors['title'] = sprintf($this->locale->read('editor', 'title_too_short'), $minLength);
        else if ($title['validator']->isTooLong($maxLength = (int) $this->config->read('max_title_length')))
            $errors['title'] = sprintf($this->locale->read('editor', 'title_too_long'), $maxLength);
        else if ($title['validator']->containsHTML())
            $errors['title'] = $this->locale->read('editor', 'title_no_html');
        else {
            if (Sanitizer::ditchUnderscores($title['value']) != Sanitizer::ditchUnderscores($this->_title))
                $this->_updatedTitle = true;

            $this->_title = Sanitizer::ditchUnderscores($title['value']);
            $this->_titleSafe = Sanitizer::addUnderscores($this->_title);
        }

        // Check for duplicate titles
        if ($id['value'] == 0 && $this->existsByName($this->_title, $id['value']))
            $errors['title'] = $this->locale->read('editor', 'title_already_in_use');

        // Body validation
        if ($body['validator']->isTooShort($minLength = (int) $this->config->read('min_body_length')))
            $errors['body'] = sprintf($this->locale->read('editor', 'body_too_short'), $minLength);
        else if ($body['validator']->isTooLong($maxLength = (int) $this->config->read('max_body_length')))
            $errors['body'] = sprintf($this->locale->read('editor', 'body_too_long'), $maxLength);
        else
            $this->_bodyUnparsed = $body['value'] ?? '';

        $this->_id = $id['value'];
        unset($this->_tempInfo);
    }

    public function update() : void {
        // New articles
        if ($this->_id == 0) {
            $query = QueryFactory::produce('modify', '
                INSERT 
                    INTO {db_prefix}article (
                        title, locale_id, `status`
                    )
                    VALUES (
                        :title, :locale_id, :status_nr
                    );

                INSERT
                    INTO {db_prefix}article_history (
                        article_id, user_id, body
                    )
                    VALUES (
                        LAST_INSERT_ID(),
                        :user_id,
                        :body
                    )'
            );

            $query->setParams([
                'title' => $this->_title,
                'locale_id' => $this->locale->getID(),
                'status_nr' => 1,
                'user_id' => 1,
                'body' => $this->_bodyUnparsed
            ]);
        }

        // Existing articles
        else {
            $raw_query = '
                INSERT
                    INTO {db_prefix}article_history (
                        article_id, user_id, body
                    )
                    VALUES (
                        :id,
                        :user_id,
                        :body
                    )';

            $query = QueryFactory::produce('modify', $raw_query);

            $params = [
                'id' => $this->_id,
                'user_id' => 1,
                'body' => $this->_bodyUnparsed
            ];

            $query->setParams($params);
        }

        $query->execute();

        if ($this->_updatedTitle) {
            $query = QueryFactory::produce('modify', '
                  UPDATE {db_prefix}article
                        SET title = :title
                        WHERE id = :id
                        LIMIT 1');

            $query->setParams([
                'title' => $this->_title,
                'id' => $this->_id
            ]);

            $query->execute();
        }
    }

    public function determineView() : string {
        return ($this->_isEditing || $this->_forceEdit) ? 'editor' : 'article';
    }

    public function getSafeTitle() : string {
        return $this->_titleSafe;
    }
}