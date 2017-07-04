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

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;

class LogEntry {

    private $_message, $_type, $_data;

    public function __construct(string $message, string $type, array $data = []) {
        $this->_message = $message;
        $this->_type = $type;
        $this->_data = $data;
    }

    public function create() : void {
        switch ($this->_type) {
            case 'error':
                $this->createErrorLogEntry();
                break;
            default:
                return;
        }
    }

    public function createErrorLogEntry() : void {
        $query = QueryFactory::produce('modify', '
            INSERT
                INTO {db_prefix}log_errors (
                    message, `data`, type, dtime
                )
                VALUES (
                    :message, :error_data, :type, NOW()
                )
        ');

        if (count($this->_data) > 0) {
            foreach ($this->_data as $key => $value)
                $this->_data[$key] = implode(':', [$key, $value]);

            $data = implode(';', $this->_data);
        }
        else
            $data = '';

        $query->setParams([
            'message' => $this->_message,
            'error_data' => $data,
            'type' => $this->_data['error_type'] ?? 0
        ]);

        $query->execute();
    }
}