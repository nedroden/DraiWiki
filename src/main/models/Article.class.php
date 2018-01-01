<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\{InputValidator, PostRequest, Sanitizer};
use DraiWiki\src\main\controllers\Locale;
use Aidantwoods\SecureParsedown\SecureParsedown;;

class Article extends ModelHeader {

    private $_requestedArticle, $_isHomepage;

    private $_id, $_title, $_titleSafe, $_details;

    /**
     * @var string $_body Parsed article body with HTML not escaped
     * @var string $_bodyUnparsed Raw article body
     * @var string $_bodySafeHTML Parsed article body with HTML escaped
     * @var string $_htmlTextArea Raw article body suitable for text areas
     */
    private $_body, $_bodyUnparsed, $_bodySafeHTML, $_htmlTextArea;

    private $_forceEdit, $_isEditing;
    private $_parsedown;
    private $_tempInfo;
    private $_updatedTitle = false;

    private $_lastUpdatedUsername;
    private $_lastUpdatedDate;

    private $_subApp;
    private $_historyTable;
    private $_request;
    private $_viewingOldVersion = false;
    private $_articleLocale;
    private $_group;

    public function __construct(?string $requestedArticle, bool $isHomepage, ?int $historicalVersion = null) {
        $this->_requestedArticle = $requestedArticle;
        $this->_isHomepage = $isHomepage;
        $this->_parsedown = new SecureParsedown();
        $this->_parsedown->setSafeMode(true);

        $this->loadLocale();
        $this->loadConfig();
        $this->loadUser();

        self::$locale->loadFile('article');
        self::$locale->loadFile('editor');
        self::$locale->loadFile('find');

        $this->load($historicalVersion);
    }

    private function load(?int $historicalVersion = null) : void {
        if (strlen($this->_requestedArticle) > self::$config->read('max_title_length'))
            $this->_requestedArticle = self::$locale->read('article', 'new_article');

        $historicalVersion = is_numeric($historicalVersion) ? $historicalVersion : null;

        $query = QueryFactory::produce('select', '
            SELECT a.id, a.title, a.locale_id, a.status, a.group_id, h.body, h.updated, u.username
                FROM {db_prefix}article a
                INNER JOIN {db_prefix}article_history h ON (a.id = h.article_id)
                INNER JOIN `{db_prefix}user` u ON (h.user_id = u.id)
                WHERE ' . ($this->_isHomepage ? 'a.id' : 'a.title') . ' = :article
                ' . (!empty($historicalVersion) ? 'AND h.id = ' . $historicalVersion : '') . '
                AND `status` = 1
                ORDER BY h.updated DESC
                LIMIT 1
        ');

        $query->setParams([
            'article' => $this->_isHomepage ? self::$locale->getHomepageID() : Sanitizer::ditchUnderscores($this->_requestedArticle)
        ]);

        $result = $query->execute();

        if (!empty($historicalVersion) && count($result) != 0)
            $this->_viewingOldVersion = true;

        // @todo Add error message if a historical version doesn't exist
        else if (!empty($historicalVersion) && count($result) == 0) {
            $this->load();
            return;
        }

        if (count($result) == 0) {
            $this->_forceEdit = true;
            $this->setArticleInfo([]);
        }

        foreach ($result as $article)
            $this->setArticleInfo($article);
    }

    private function setArticleInfo(array $info) : void {
        $this->_id = $info['id'] ?? 0;
        $this->_title = $info['title'] ?? Sanitizer::escapehtml($this->_requestedArticle) ?? self::$locale->read('article', 'new_article');
        $this->_titleSafe = Sanitizer::addUnderscores($this->_title);

        $this->_body = $this->_parsedown->text($info['body'] ?? '');
        $this->_bodyUnparsed = $info['body'] ?? '';
        $this->_bodySafeHTML = $this->_parsedown->setMarkupEscaped(true)->text($info['body'] ?? '');
        $this->_htmlTextArea = htmlspecialchars($info['body'] ?? '', ENT_NOQUOTES, 'UTF-8');

        $this->_lastUpdatedUsername = $info['username'] ?? self::$locale->read('auth', 'guest');
        $this->_lastUpdatedDate = $info['updated'] ?? 'unknown';

        // Status IDs should be integers. Always.
        $status = !empty($info['status']) ? (int) $info['status'] : 0;

        $this->_details = [
            'status' => $status
        ];

        $this->_articleLocale = !empty($info['locale_id']) ? new Locale($info['locale_id']) : self::$locale->getID();

        $this->_group = $info['group_id'] ?? 0;
    }

    private function getAdditionalData() : array {
        $data = [];

        if ($this->_isEditing || $this->_forceEdit) {
            $data['action'] = self::$config->read('url') . '/index.php/article/' . $this->_titleSafe . '/edit';
        }
        else if ($this->_subApp == 'translations') {
            $data['action'] = self::$config->read('url') . '/index.php/article/' . $this->_titleSafe . '/assigntranslations';

            // Yep. We know. Really. But unfortunately Dwoo doesn't have a built-in sprintf function, so we have to do it like this
            $data['remove_text'] = sprintf(self::$locale->read('article', 'declare_independence_desc'),
                            self::$config->read('url') . '/index.php/article/' . $this->_titleSafe . '/removetranslationgroup');

            $data['can_declare_independence'] = self::$user->hasPermission('remove_from_translation_group') && $this->_id != self::$locale->getHomepageID();
        }

        if (!empty($this->_historyTable))
            $data['history_table'] = $this->_historyTable;

        return $data;
    }

    private function existsById(int $id) : bool {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM {db_prefix}article
                WHERE id = :id
                AND `status` != :art_status
                LIMIT 1
        ');

        $query->setParams([
            'id' => $id,
            'art_status' => 2
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
                AND `status` != :art_status
                LIMIT 1
        ');

        $query->setParams([
            'id' => $id,
            'title' => $title,
            'art_status' => 2
        ]);

        foreach ($query->execute() as $entry)
            return true;

        return false;
    }

    public function getTitle() : string {
        switch ($this->_subApp) {
            case 'delete':
                return self::$locale->read('article', 'deleting');
            case 'edit':
                return self::$locale->read('article', 'editing') . $this->_title;
            case 'translations':
                return self::$locale->read('article', 'assign_translations');
            case 'history':
                return self::$locale->read('article', 'history');
            default:
                return $this->_title;
        }
    }

    public function prepareData() : array {
        $additionalData = $this->getAdditionalData();

        return [
            'id' => $this->_id,
            'title' => ucfirst($this->_title),
            'title_real' => $this->_title,
            'title_safe' => $this->_titleSafe,
            'body' => $this->_body,
            'body_unparsed' => $this->_bodyUnparsed,
            'body_safe' => $this->_bodySafeHTML,
            'body_text_area' => $this->_htmlTextArea,
            'last_updated_by' => $this->getLastUpdatedTime(),
            'historical_version' => $this->_viewingOldVersion
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
            $errors['id'] = self::$locale->read('editor', 'invalid_id');
            return;
        }

        $id['value'] = (int) $id['value'];

        if ($id['value'] != 0 && !$this->existsById($id['value'])) {
            $errors['id'] = self::$locale->read('editor', 'article_not_found');
            return;
        }

        // Check the length of the title
        if ($title['validator']->isTooShort($minLength = (int) self::$config->read('min_title_length')))
            $errors['title'] = sprintf(self::$locale->read('editor', 'title_too_short'), $minLength);
        else if ($title['validator']->isTooLong($maxLength = (int) self::$config->read('max_title_length')))
            $errors['title'] = sprintf(self::$locale->read('editor', 'title_too_long'), $maxLength);
        else if ($title['validator']->containsHTML())
            $errors['title'] = self::$locale->read('editor', 'title_no_html');
        else {
            if (Sanitizer::ditchUnderscores($title['value']) != Sanitizer::ditchUnderscores($this->_title))
                $this->_updatedTitle = true;

            $this->_title = Sanitizer::ditchUnderscores($title['value']);
            $this->_titleSafe = Sanitizer::addUnderscores($this->_title);
        }

        // Check for duplicate titles
        if ($id['value'] == 0 && $this->existsByName($this->_title, $id['value']))
            $errors['title'] = self::$locale->read('editor', 'title_already_in_use');

        // Body validation
        if ($body['validator']->isTooShort($minLength = (int) self::$config->read('min_body_length')))
            $errors['body'] = sprintf(self::$locale->read('editor', 'body_too_short'), $minLength);
        else if ($body['validator']->isTooLong($maxLength = (int) self::$config->read('max_body_length')))
            $errors['body'] = sprintf(self::$locale->read('editor', 'body_too_long'), $maxLength);
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
                'locale_id' => self::$locale->getID(),
                'status_nr' => 1,
                'user_id' => self::$user->getID(),
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
                'user_id' => self::$user->getID(),
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

    public function softDelete() : bool {
        if ($this->_id == 0)
            return false;

        $query = QueryFactory::produce('modify', '
            UPDATE {db_prefix}article
                SET `status` = 2
                WHERE id = :id
        ');

        $query->setParams(['id' => $this->_id]);
        $query->execute();

        return true;
    }

    public function getHistory(int $start = 0) : array {
        $query = QueryFactory::produce('select', '
            SELECT h.id, h.updated, u.username
                FROM {db_prefix}article_history h
                LEFT JOIN {db_prefix}user u ON (u.id = h.user_id)
                WHERE article_id = :article_id
                ORDER BY updated DESC
                LIMIT ' . $start . ', ' . self::$config->read('max_results_per_page'));

        $query->setParams([
            'article_id' => $this->_id
        ]);

        $result = [];
        foreach ($query->execute() as $article) {
            $result[] = [
                'updated' => '<a href=\"' . self::$config->read('url') . '/index.php/article/' . $this->_titleSafe . '/history/' . $article['id'] . '\">' . $article['updated'] . '</a>',
                'username' => $article['username']
            ];
        }

        return $result;
    }

    public function createHistoryTable() : void {
        $columns = [
            'updated',
            'username'
        ];

        $table = new Table('article', $columns, []);
        $table->setID('user_list');

        $table->create();
        $this->_historyTable = $table->returnTable();
    }

    private function getHistoryCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) AS num
                FROM `{db_prefix}article_history`
                WHERE article_id = :article_id
        ');

        $query->setParams([
            'article_id' => $this->_id
        ]);

        foreach ($query->execute() as $record)
            return (int) $record['num'];

        return 0;
    }

    private function getStart() : int {
        if (!empty($_REQUEST['start']) && is_numeric($_REQUEST['start']) && ((int) $_REQUEST['start']) <= $this->getHistoryCount()) {
            return (int) $_REQUEST['start'];
        }
        else
            return 0;
    }

    public function generateJSON() : string {
        if ($this->_request == 'getlist') {
            $historyCount = $this->getHistoryCount();

            $start = $this->getStart();
            $end = $start + self::$config->read('max_results_per_page');

            if ($end > $historyCount)
                $end = $start + ($historyCount % self::$config->read('max_results_per_page'));

            $jsonRequest = '
            {
                "start": "' . $start . '",
                "end": "' . $end . '",
                "total_records": "' . $historyCount . '",
                "displayed_records": "' . self::$config->read('max_results_per_page') . '",
                "data": [';

            $jsonHistory = [];
            foreach ($this->getHistory() as $article) {
                $jsonHistory[] = '
                {
                    "updated": "' . $article['updated'] . '",
                    "username": "' . $article['username'] . '"
                }';
            }

            $jsonRequest .= implode(',', $jsonHistory) . '
                ]
            }';

            return $jsonRequest;
        }

        else
            return '';
    }

    public function getSidebarLanguages() : array {
        if ($this->_forceEdit || $this->_isEditing)
            return [];

        $query = QueryFactory::produce('select', '
			SELECT a.title, a.locale_id
				FROM {db_prefix}article a
				INNER JOIN {db_prefix}locale l ON (a.locale_id = l.id)
				WHERE a.locale_id != :locale
				AND a.group_id = :group
				AND a.group_id != 0
		');

        $query->setParams([
            'locale' => $this->_articleLocale->getID(),
            'group' => $this->_group
        ]);

        $result = $query->execute();
        $locales = [];
        foreach ($result as $locale) {
            $localeObject = $locale['locale_id'] == self::$locale->getID() ? self::$locale : new Locale($locale['locale_id']);

            $locales[] = [
                'label' => $localeObject->getNative(),
                'href' => self::$config->read('url') . '/index.php/locale/' . $localeObject->getCode() . '/' . Sanitizer::addUnderscores($locale['title']),
                'visible' => true,
                'hardcoded' => true
            ];
        }

        return $locales;
    }

    public function updateGroupId() : string {
        $articleId = $_POST['article_id'] ?? 0;

        if (!is_numeric($articleId) || (int) $articleId === 0)
            return 'invalid_article';
        else if ((int) $articleId == self::$locale->getId())
            return 'group_invalid_locale';

        $query = QueryFactory::produce('select', '
            SELECT id, group_id, locale_id
                FROM {db_prefix}article
                WHERE id = :id
        ');

        $query->setParams(['id' => (int) $articleId]);
        $result = $query->execute();

        if (count($result) == 0)
            return 'invalid_article';

        foreach ($result as $article) {
            $newGroupId = (int) $article['group_id'];

            if ($newGroupId == 0) {
                $query = QueryFactory::produce('select', '
                    SELECT MAX(group_id) as group_id
                        FROM {db_prefix}article
                ');

                foreach ($query->execute() as $highGroupNumberArticle)
                    $newGroupId = (int) $highGroupNumberArticle['group_id'] + 1;

                $query = QueryFactory::produce('modify', '
                    UPDATE {db_prefix}article
                        SET group_id = :group_id
                        WHERE id = :id
                ');

                $query->setParams([
                    'id' => $article['id'],
                    'group_id' => $newGroupId
                ]);

                $query->execute();
            }

            $query = QueryFactory::produce('modify', '
                UPDATE {db_prefix}article
                    SET group_id = :group_id
                    WHERE id = :id
            ');

            $query->setParams([
                'group_id' => $newGroupId,
                'id' => $this->_id
            ]);

            $query->execute();

            $this->_group = (int) $article['group_id'];
            break;
        }

        return '';
    }

    public function removeFromTranslationGroup() : void {
        $query = QueryFactory::produce('modify', '
            UPDATE {db_prefix}article
                SET group_id = 0
                WHERE id = :id
        ');

        $query->setParams([
            'id' => $this->_id
        ]);

        $query->execute();
    }

    public function getLastUpdatedTime() : string {
        return sprintf(self::$locale->read('article', 'last_updated_by'), $this->_lastUpdatedUsername, $this->_lastUpdatedDate);
    }

    public function getSafeTitle() : string {
        return $this->_titleSafe;
    }

    public function getID() : int {
        return $this->_id;
    }

    public function getIsHomepage() : bool {
        return $this->_id == self::$locale->getHomepageID();
    }

    public function getIsEditing() : bool {
        return $this->_forceEdit || $this->_isEditing;
    }

    public function getArticleLocale() : Locale {
        return $this->_articleLocale;
    }

    public function getGroup() : int {
        return $this->_group;
    }

    public function setIsEditing(bool $status) : void {
        $this->_isEditing = $status;
    }

    public function determineView() : string {
        if ($this->_isEditing || $this->_forceEdit)
            return 'editor';

        switch ($this->_subApp) {
            case 'translations':
                return 'assign_translations';
            case 'history':
                return 'history';
            default:
                return 'article';
        }
    }

    public function setSubApp(string $subApp) : void {
        $this->_subApp = $subApp;
    }

    public function setRequest(string $request) : void {
        $this->_request = $request;
    }
}