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
        $this->loadUser();

        $this->_users = [];
        $this->_table = null;

        self::$locale->loadFile('management');
    }

    private function createTable() : void {
        $columns = [
            'username',
            'first_name',
            'last_name',
            'email_address',
            'registration_date',
            'primary_group',
            'sex',
            'manage_buttons'
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
        $end = !empty($_REQUEST['length']) && is_numeric($_REQUEST['length']) ? (int) $_REQUEST['length'] : self::$config->read('max_results_per_page');

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
        switch ($this->_request) {
            case 'getlist':
                $userCount = $this->getUserCount();

                $start = $this->getStart();
                $end = $start + self::$config->read('max_results_per_page');

                if ($end > $userCount)
                    $end = $start + ($userCount % self::$config->read('max_results_per_page'));

                $users = [];
                foreach ($this->_users as $user) {
                    $users[] = [
                        'username' => ($user->getIsActivated() == 1 ? $user->getUsername() : '<em>' . $user->getUsername() . '</em>'),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'email_address' => $user->getEmail(),
                        'registration_date' => $user->getRegistrationDate(),
                        'primary_group' => $user->getPrimaryGroupWithColor(),
                        'sex' => self::$locale->read('auth', 'sex_' . $user->getSex()),
                        'manage_buttons' => $this->generateManagementLinks($user->getID())
                    ];
                }

                return json_encode([
                    'start' => $start,
                    'end' => $end,
                    'total_records' => $userCount,
                    'displayed_records' => self::$config->read('max_results_per_page'),
                    'data' => $users
                ]);

            default:
                return '';
        }
    }

    public function deleteUser(&$errors, int $id) : void {
        if ($id == self::$user->getID()) {
            $errors[] = self::$locale->read('management', 'cannot_delete_yourself');
            return;
        }

        $user = new User($id);

        if ($user->isGuest()) {
            $errors[] = self::$locale->read('management', 'account_not_found');
            return;
        }
        else if ($user->isRoot()) {
            $errors[] = self::$locale->read('management', 'cannot_delete_root');
            return;
        }

        $user->delete();
    }

    /**
     * Generates management action links (i.e. edit | remove). Apologies for the mess, but since
     * these links are used in JSon, double quotes have to be escaped.
     * @param int $userID
     * @return string
     */
    private function generateManagementLinks(int $userID) : string {
        return '<a href=\"' . self::$config->read('url') . '/index.php/account/settings/' . $userID . '\">' . self::$locale->read('management', 'edit_user') . '</a> | <a href=\"javascript:void(0);\" onclick=\"requestConfirm(\'' . self::$config->read('url') . '/index.php/management/users/delete/' . $userID . '\')\">' . self::$locale->read('management', 'remove_user') . '</a>';
    }
}