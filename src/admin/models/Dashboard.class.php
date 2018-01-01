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

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\Sanitizer;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\ModelHeader;
use DraiWiki\src\main\models\Table;

class Dashboard extends ModelHeader {

    private $_request;

    private const MAX_EDITS_PER_PAGE = 10;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->loadUser();
    }

    public function prepareData(): array {
        return [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'loaded_extensions' => implode(', ', get_loaded_extensions()),
            'mysql_version' => $this->getMysqlVersion(),
            'draiwiki_version' => Main::WIKI_VERSION,
            'edits_this_week' => $this->getEditsThisWeek(),

            'total_number_of_edits' => $this->getLastEditsCount(),
            'total_number_of_articles' => $this->getArticleCount(),
            'total_number_of_users' => $this->getUserCount(),

            'default_locale' => self::$config->read('locale'),
            'default_templates' => self::$config->read('templates'),
            'default_skins' => self::$config->read('skins'),
            'default_images' => self::$config->read('images'),

            'recent_edits_table' => $this->getRecentEditsTable()
        ];
    }

    private function getMysqlVersion() : string {
        $query = QueryFactory::produce('select', '
            SELECT VERSION() as db_version
        ');

        $result = $query->execute();

        foreach ($result as $record)
            return $record['db_version'];

        return self::$locale->read('management', 'unknown');
    }

    private function getEditsThisWeek() : array {
        $lastSevenDays = [];
        $edits = [];

        for ($i = 0; $i < 7; $i++) {
            if ($i != 0)
                $lastSevenDays[] = date('Y-m-d', strtotime(date('Y-m-d') . ' ' . $i . 'day' . ($i != 1 ? 's' : '') . ' ago'));
            else
                $lastSevenDays[] = date('Y-m-d');

            $edits[$lastSevenDays[$i]] = ['key' => $lastSevenDays[$i], 'value' => 0];
        }

        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) AS num_edits, DATE(updated) AS update_date
                FROM {db_prefix}article_history
                WHERE updated >= NOW() - INTERVAL 7 DAY
                GROUP BY DATE(updated)
        ');

        $result = $query->execute();

        foreach ($result as $record) {
            if (empty($record['update_date']))
                continue;

            $date = $record['update_date'];
            $edits[$date] = ['key' => $date, 'value' => $record['num_edits']];
        }

        $keys = [];
        $values = [];
        foreach ($edits as $edit) {
            $keys[] = "'" . $edit['key'] . "'";
            $values[] = $edit['value'];
        }

        krsort($keys);
        krsort($values);

        return [
            'keys' => implode(', ', $keys),
            'values' => implode(', ', $values)
        ];
    }

    private function getRecentEditsTable() : string {
        $columns = [
            'article',
            'updated_by',
            'date'
        ];

        $table = new Table('management', $columns, $this->getLastEdits());
        $table->setID('user_list');

        $table->create();
        return $table->returnTable();
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'dashboard_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'dashboard_title');
    }

    public function setRequest(string $request) : void {
        $this->_request = $request;
    }

    private function getUserCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) as num
                FROM {db_prefix}user
                WHERE activated = 1
        ');

        foreach ($query->execute() as $record)
            return $record['num'];

        return 0;
    }

    private function getArticleCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) as num
                FROM {db_prefix}article
                WHERE `status` = 1
        ');

        foreach ($query->execute() as $record)
            return $record['num'];

        return 0;
    }

    private function getStart(int $recordCount) : int {
        if (!empty($_REQUEST['start']) && is_numeric($_REQUEST['start']) && ((int) $_REQUEST['start']) <= $recordCount) {
            return (int) $_REQUEST['start'];
        }
        else
            return 0;
    }

    private function getLastEdits(int $start = 0) : array {
        $edits = [];

        $query = QueryFactory::produce('select', '
            SELECT h.updated, a.title, u.username
                FROM {db_prefix}article_history h
                INNER JOIN {db_prefix}article a ON (h.article_id = a.id)
                INNER JOIN {db_prefix}user u ON (h.user_id = u.id)
                ORDER BY h.updated DESC
                LIMIT ' . $start . ', ' . self::MAX_EDITS_PER_PAGE);

        foreach ($query->execute() as $record) {
            $edits[] = [
                'title' => '<a href=\"' . self::$config->read('url') . '/index.php/article/' . Sanitizer::addUnderscores($record['title']) . '\" target=\"_blank\">' . $record['title'] . '</a>',
                'username' => $record['username'],
                'updated' => $record['updated']
            ];
        }

        return $edits;
    }

    private function getLastEditsCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) as num
                FROM {db_prefix}article_history
        ');

        $result = $query->execute();

        foreach ($result as $record)
            return (int) $record['num'];

        return 0;
    }

    public function generateJSON() : string {
        switch ($this->_request) {
            case 'getrecentedits':
                $recordCount = $this->getLastEditsCount();

                $start = $this->getStart($recordCount);
                $end = $start + self::MAX_EDITS_PER_PAGE;

                if ($end > $recordCount)
                    $end = $start + ($recordCount % self::MAX_EDITS_PER_PAGE);

                $edits = [];
                foreach ($this->getLastEdits($start) as $record) {
                    $edits[] = [
                        'title' => $record['title'],
                        'username' => $record['username'],
                        'updated' => $record['updated']
                    ];
                }

                return json_encode([
                    'start' => $start,
                    'end' => $end,
                    'total_records' => $recordCount,
                    'displayed_records' => self::MAX_EDITS_PER_PAGE,
                    'data' => $edits
                ]);

            default:
                return '';
        }
    }
}