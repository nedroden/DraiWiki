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

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\ModelHeader;

class Dashboard extends ModelHeader {

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
    }

    public function prepareData(): array {
        return [
            'php_version' => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'],
            'loaded_extensions' => implode(', ', get_loaded_extensions()),
            'mysql_version' => $this->getMysqlVersion(),
            'draiwiki_version' => Main::WIKI_VERSION,
            'edits_this_week' => $this->getEditsThisWeek(),

            'default_locale' => $this->config->read('locale'),
            'default_templates' => $this->config->read('templates'),
            'default_skins' => $this->config->read('skins'),
            'default_images' => $this->config->read('images')
        ];
    }

    private function getMysqlVersion() : string {
        $query = QueryFactory::produce('select', '
            SELECT VERSION() as db_version
        ');

        $result = $query->execute();

        foreach ($result as $record)
            return $record['db_version'];

        return $this->locale->read('management', 'unknown');
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

    public function getPageDescription() : string {
        return $this->locale->read('management', 'dashboard_description');
    }

    public function getTitle() : string {
        return $this->locale->read('management', 'dashboard_title');
    }
}