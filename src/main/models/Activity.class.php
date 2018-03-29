<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\main\models\Article;

class Activity extends ModelHeader {

    public const TYPE_UNKNOWN = 0x00;
    public const TYPE_ARTICLE_CREATED = 0x01;
    public const TYPE_ARTICLE_UPDATED = 0x02;
    public const TYPE_REGISTRATION = 0x03;

    private $_resultsPerPage;

    private $_resultCount;
    private $_maxResults;
    private $_updates;

    private $_request;

    public function __construct() {
        $this->loadLocale();

        $this->_resultsPerPage = self::$config->read('max_results_per_page');
        $this->_resultCount = $this->getResultCount();
    }

    public function prepareData() : array {
        return [
            'show_load_more' => $this->_resultCount > $this->_maxResults,
            'updates' => $this->_updates,
            'results_per_page' => $this->_resultsPerPage
        ];
    }

    private function getResultCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT count(id) as resultCount
                FROM {db_prefix}log_changes
        ');

        return $query->execute()[0]['resultCount'] ?? 0;
    }

    public function loadUpdates(int $start = 0) : array {
        $limit = self::$config->read('max_results_per_page');

        $query = QueryFactory::produce('select', '
            SELECT c.type, c.creation_date, c.user_id, c.article_id, a.title
                FROM {db_prefix}log_changes c
                LEFT JOIN {db_prefix}article a ON (a.id = c.article_id)
                WHERE c.locale_id = :locale
                ORDER BY creation_date DESC
                LIMIT ' . $start . ', ' . $limit);

        $query->setParams([
            'locale' => self::$locale->getCurrentLocaleInfo()->getId()
        ]);

        $updates = [];
        $users = [];

        foreach ($query->execute() as $update) {
            if (!empty($update['user_id']) && empty($users[$update['user_id']]))
                $users[$update['user_id']] = new User($update['user_id']);

            $user = $users[$update['user_id']] ?? new User(0);

            switch ($update['type']) {
                case self::TYPE_ARTICLE_CREATED:
                    if ($update['article_id'] == null)
                        continue 2;

                    $title = Sanitizer::escapeHTML($update['title']);
                    $urlTitle = Sanitizer::addUnderscores($title);

                    $text = _localized('main.created_article', $user->getUsername(), $urlTitle, $title);
                    break;
                case self::TYPE_ARTICLE_UPDATED:
                    if ($update['article_id'] == null)
                        continue 2;

                    $title = Sanitizer::escapeHTML($update['title']);
                    $urlTitle = Sanitizer::addUnderscores($title);

                    $text = _localized('main.updated_article', $user->getUsername(), $urlTitle, $title);
                    break;
                case self::TYPE_REGISTRATION:
                    $text = _localized('main.registered_an_account', $user->getUsername());
                    break;
                case self::TYPE_UNKNOWN:
                default:
                    continue 2;
            }

            $updates[] = [
                'text' => $text,
                'time' => $update['creation_date']
            ];
        }

        $this->_updates = $updates;

        return $this->_updates;
    }

    private function getStart() : int {
        if (!empty($_REQUEST['start']) && is_numeric($_REQUEST['start']) && ((int) $_REQUEST['start']) <= $this->_resultCount)
            return (int) $_REQUEST['start'];

        return 0;
    }

    public function generateJSON() : string {
        $start = $this->getStart();
        $end = $start + $this->_resultsPerPage;

        if ($end > $this->_resultCount)
            $end = $start + ($this->_resultCount - $start);

        $data = [];
        foreach ($this->loadUpdates($start) as $update) {
            $data[] = [
                'text' => $update['text'],
                'time' => $update['time']
            ];
        }

        return json_encode([
            'start' => $start,
            'end' => $end,
            'data' => $data,
            'total_records' => $this->_maxResults
        ]);
    }

    public static function add(?int $userId = null, int $localeId, ?int $articleId = null, int $type) : void {
        $query = QueryFactory::produce('modify', '
            INSERT INTO {db_prefix}log_changes (
                user_id, locale_id, article_id, type, creation_date
            )

            VALUES (
                :user_id,
                :locale_id,
                :article_id,
                :type,
                NOW()
            )
        ');

        $query->setParams([
            'user_id' => $userId,
            'locale_id' => $localeId,
            'article_id' => $articleId,
            'type' => $type
        ]);

        $query->execute();
    }

    public function getTitle() : string {
        return _localized('main.activity');
    }
}