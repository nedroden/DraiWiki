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
use DraiWiki\src\main\models\ModelHeader;

class UserManagement extends ModelHeader {

    private $_request, $_users;

    private const MAX_USERS_PER_PAGE = 25;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        $this->_users = [];
    }

    public function getPageDescription() : string {
        return $this->locale->read('management', 'users_display_description');
    }

    public function getTitle() : string {
        return $this->locale->read('management', 'users_display');
    }

    public function setRequest(string $request) : void {
        $this->_request = $request;
    }

    public function loadUsers(int $start = 0) : void {
        $end = $start + ($_REQUEST['length'] ?? self::MAX_USERS_PER_PAGE);

        $query = QueryFactory::produce('select', '
            SELECT id
                FROM `{db_prefix}user`
                LIMIT ' . $start . ', ' . $end);

        $result = $query->execute();

        foreach ($result as $record)
            $this->_users[] = new User($record['id']);
    }

    private function getUserCount() : int {
        $query = QueryFactory::produce('select', '
            SELECT COUNT(id) as num
                FROM `{db_prefix}user`
        ');

        foreach ($query->execute() as $record)
            return $record['num'];

        return 0;
    }

    public function generateJSON() : string {
        if ($this->_request == 'getlist') {
            $jsonRequest = '
            {
                "draw": "' . (int) $_REQUEST['draw'] . '",
                "recordsTotal": "' . $this->getUserCount() . '",
                "recordsFiltered": "' . $this->getUserCount() . '",
                "data": [';

            $jsonUsers = [];
            foreach ($this->_users as $user) {
                $jsonUsers[] = '
                {
                    "username": "' . $user->getUsername() . '",
                    "first_name": "' . $user->getFirstName() . '",
                    "last_name": "' . $user->getLastName() . '",
                    "email_address": "' . $user->getEmail() . '",
                    "sex": "' . $this->locale->read('auth', 'sex_' . $user->getSex()) . '"
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