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
use DraiWiki\src\main\models\{ModelHeader, Table};

class UploadManagement extends ModelHeader {

    private $_table, $_request;

    private const MAX_UPLOADS_PER_PAGE = 20;

    public function __construct() {
        $this->loadLocale();
        self::$locale->loadFile('management');
    }

    public function generateTable() : void {
        $columns = [
            'preview',
            'filename',
            'poster',
            'upload_date',
            'file_type'
        ];

        $table = new Table('management', $columns, []);
        $table->setID('user_list');

        $table->create();
        $this->_table = $table->returnTable();
    }

    public function prepareData() : array {
        $this->generateTable();

        return [
            'table' => $this->_table
        ];
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'upload_management_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'upload_management');
    }

    private function getUploads(int $start = 0) : array {
        $uploads = [];

        $query = QueryFactory::produce('select', '
            SELECT f.original_name, f.type, f.upload_date, u.username
                FROM {db_prefix}upload f
                INNER JOIN {db_prefix}user u ON (f.user_id = u.id)
                ORDER BY f.upload_date DESC
                LIMIT ' . $start . ', ' . self::MAX_UPLOADS_PER_PAGE);

        foreach ($query->execute() as $record) {
            $uploads[] = [
                'preview' => $this->getPreview($record['original_name'], $record['type']),
                'filename' => $record['original_name'],
                'poster' => $record['username'],
                'upload_date' => $record['upload_date'],
                'file_type' => self::$locale->read('management', 'file_' . $record['type'])
            ];
        }

        return $uploads;
    }

    public function getUploadCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) AS num
                FROM {db_prefix}upload
        ');

        foreach ($query->execute() as $record)
            return (int) $record['num'];

        return 0;
    }

    private function getStart(int $recordCount) : int {
        if (!empty($_REQUEST['start']) && is_numeric($_REQUEST['start']) && ((int) $_REQUEST['start']) <= $recordCount) {
            return (int) $_REQUEST['start'];
        }
        else
            return 0;
    }

    public function generateJSON() : string {
        if ($this->_request == 'getlist') {
            $recordCount = $this->getUploadCount();

            $start = $this->getStart($recordCount);
            $end = $start + self::MAX_UPLOADS_PER_PAGE;

            if ($end > $recordCount)
                $end = $start + ($recordCount % self::MAX_UPLOADS_PER_PAGE);

            $jsonRequest = '
            {
                "start": "' . $start . '",
                "end": "' . $end . '",
                "total_records": "' . $recordCount . '",
                "displayed_records": "' . self::MAX_UPLOADS_PER_PAGE . '",
                "data": [';

            $jsonEdits = [];
            foreach ($this->getUploads($start) as $record) {
                $jsonEdits[] = '
                {
                    "preview": "' . $record['preview'] . '",
                    "filename": "' . $record['filename'] . '",
                    "poster": "' . $record['poster'] . '",
                    "upload_date": "' . $record['upload_date'] . '",
                    "file_type": "' . $record['file_type'] . '"
                }';
            }

            $jsonRequest .= implode(',', $jsonEdits) . '
                ]
            }';

            return $jsonRequest;
        }

        else
            return '';
    }

    public function setRequest(string $request) : void {
        $this->_request = $request;
    }

    private function getPreview(string $filename, string $type) : string {
        switch ($type) {
            case 'avatar':
            case 'uploaded_image':
                return '<img src=\"' . self::$config->read('url') . '/public/ImageDispatch.php?filename=' . $filename . '\" alt=\"*\" class=\"image_preview\" />';
            default:
                return '';
        }
    }
}