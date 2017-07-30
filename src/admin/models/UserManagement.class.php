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

use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\main\models\{ModelHeader, Table};

class UserManagement extends ModelHeader {

    private $_request, $_users, $_table;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        $this->_users = [];
        $this->_table = [];
    }

    private function createTable() : void {
        $columns = [
            'username',
            'first_name',
            'last_name',
            'email_address',
            'registration_date',
            'primary_group',
            'sex'
        ];

        $table = new Table('management', $columns, []);
        $table->setID('user_list');

        $table->create();
        $this->_table = $table->returnTable();
    }

    public function prepareData() : array {
        $this->createTable();

        return [
            'table' => $this->_table
        ];
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'users_display_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'users_display');
    }

    public function setRequest(string $request) : void {
        $this->_request = $request;
    }

    public function loadUsers(int $start = 0) : void {
        $end = ($_REQUEST['length'] ?? self::$config->read('max_results_per_page'));

        $query = QueryFactory::produce('select', '
            SELECT id
                FROM `{db_prefix}user`
                ORDER BY username ASC
                LIMIT ' . $start . ', ' . $end);

        $result = $query->execute();

        foreach ($result as $record)
            $this->_users[] = new User($record['id']);
    }

    private function getUserCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) AS num
                FROM `{db_prefix}user`
        ');

        foreach ($query->execute() as $record)
            return (int) $record['num'];

        return 0;
    }

    private function getStart() : int {
        if (!empty($_REQUEST['start']) && is_numeric($_REQUEST['start']) && ((int) $_REQUEST['start']) <= $this->getUserCount()) {
            return (int) $_REQUEST['start'];
        }
        else
            return 0;
    }

    public function generateJSON() : string {
        if ($this->_request == 'getlist') {
            $userCount = $this->getUserCount();

            $start = $this->getStart();
            $end = $this->getStart() + self::$config->read('max_results_per_page');

            if ($end > $userCount)
                $end = $start + ($userCount % self::$config->read('max_results_per_page'));

            $jsonRequest = '
            {
                "start": "' . $start . '",
                "end": "' . $end . '",
                "total_records": "' . $userCount . '",
                "displayed_records": "' . self::$config->read('max_results_per_page') . '",
                "data": [';

            $jsonUsers = [];
            foreach ($this->_users as $user) {
                $jsonUsers[] = '
                {
                    "username": "' . $user->getUsername() . '",
                    "first_name": "' . $user->getFirstName() . '",
                    "last_name": "' . $user->getLastName() . '",
                    "email_address": "' . $user->getEmail() . '",
                    "registration_date": "' . $user->getRegistrationDate() . '",
                    "primary_group": "' . $user->getPrimaryGroupWithColor() . '",
                    "sex": "' . self::$locale->read('auth', 'sex_' . $user->getSex()) . '"
                }';
            }

            $jsonRequest .= implode(',', $jsonUsers) . '
                ]
            }';

            return $jsonRequest;
        }

        else
            return '';
    }
}