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

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\Session;
use DraiWiki\src\main\models\ModelHeader;

class User extends ModelHeader {

    // Note: passwords are hashed before they arrive here
    private $_id, $_username, $_password, $_firstName, $_lastName;
    private $_email, $_primaryGroup, $_groups;
    private $_sex, $_birthdate, $_ip, $_cookieLength;

    private $_sessionUID = 0;

    public function __construct(int $specificUser = null, array $details = []) {
        $this->loadConfig();
        $this->loadLocale();

        $this->locale->loadFile('auth');

        $this->setSessionUID();

        // If we're dealing with a registration we shouldn't attempt to load user information from the database
        if (empty($details) && empty($specificUser))
            $this->load();
        else if (empty($details) && !empty($specificUser))
            $this->load($specificUser);
        else
            $this->setUserInfo($details);
    }

    private function load(int $specificUser = null) : void {
        $uid = $specificUser ?? $this->_sessionUID;

        $query = QueryFactory::produce('select', '
            SELECT id, username, email_address, sex, birthdate, first_name, last_name, 
                    ip_address, registration_date, group_id, secondary_groups
                FROM `{db_prefix}user`
                WHERE id = :uid
        ');

        $query->setParams([
            'uid' => $uid
        ]);

        foreach ($query->execute() as $user) {
            $this->setUserInfo($user);
            return;
        }

        $this->setUserInfo([
            'group_id' => 5
        ]);
    }

    private function validate() : array {
        $errors = [];

        $queries = [
            'username' => 'SELECT id
                FROM `{db_prefix}user`
                WHERE username = :username
            ',
            'email_address' => 'SELECT id
                FROM `{db_prefix}user`
                WHERE email_address = :email
            '];

        foreach ($queries as $key => $rawQuery) {
            $query = QueryFactory::produce('select', $rawQuery);

            if ($key == 'username')
                $query->setParams(['username' => $this->_username]);
            else if ($key == 'email_address')
                $query->setParams(['email' => $this->_email]);

            foreach ($query->execute() as $record)
                $errors[$key] = $this->locale->read('auth', $key . '_in_use');
        }

        return $errors;
    }

    private function setUserInfo(array $details) : void {
        $this->_id = $details['id'] ?? 0;
        $this->_username = $details['username'] ?? $this->locale->read('auth', 'guest');

        /* Again, passwords are HASHED before they arrive here. Passwords are stored ONLY during the
         registration process and are removed as soon as the user has been added to the database */
        $this->_password = $details['password'] ?? '';

        $this->_firstName = $details['first_name'] ?? $this->locale->read('auth', 'john');
        $this->_lastName = $details['last_name'] ?? $this->locale->read('auth', 'doe');

        $this->_primaryGroup = $details['group_id'] ?? 4;

        // We're dealing with a new user here, so we don't have any secondary groups
        if (empty($details['group_id']))
            $this->_groups = $this->_primaryGroup;
        else if (!empty($details['secondary_groups']))
            $this->_groups = implode(', ', array_merge([$details['group_id']], $details['secondary_group']));
        else
            $this->_groups = $details['group_id'];

        $this->_ip = $details['ip'] ?? '127.0.0.1';

        $this->_sex = $details['sex'] ?? 0;
        $this->_birthdate = $details['birthdate'] ?? '';
        $this->_email = $details['email'] ?? 'nobody@example.com';

        $this->_cookieLength = $details['cookie_length'] ?? 7 * 24 * 60 * 60;
    }

    public function create(array &$errors) : void {
        if (count($errors) == 0)
            $errors = array_merge($errors, $this->validate());

        if (count($errors) > 0)
            return;

        $query = QueryFactory::produce('modify', '
            INSERT
                INTO `{db_prefix}user` (
                    username, `password`, email_address, sex, birthdate, first_name, last_name, 
                    ip_address, group_id, secondary_groups
                )
                VALUES (
                    :username, :passw, :email, :sex, :birthdate,
                    :first_name, :last_name, :ip_address,
                    :group_id, :secondary_groups
                )
        ');

        $groups = explode(', ', $this->_groups);

        // Don't include the primary user groups as a secondary group
        if (count($groups) == 0 || (count($groups) == 1 && $groups[0] == $this->_primaryGroup))
            $groups = null;
        else {
            unset($groups[0]);
            $groups = count($groups) >= 1 ? implode(', ', $groups) : null;
        }

        $query->setParams([
            'username' => $this->_username,
            'passw' => $this->_password,
            'email' => $this->_email,
            'sex' => $this->_sex,
            'birthdate' => $this->_birthdate,
            'first_name' => $this->_firstName,
            'last_name' => $this->_lastName,
            'ip_address' => $this->_ip,
            'group_id' => $this->_primaryGroup,
            'secondary_groups' => $groups
        ]);

        $query->execute();

        unset($this->_password);
    }

    /**
     * The user info should already have been set, so we have all the information we need.
     * @param array $errors Any errors that may arise are added to this array (passed by reference).
     * @return void
     */
    public function login(array &$errors) : void {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM `{db_prefix}user`
                WHERE email_address = :email
                AND `password` = :passw
        ');

        $query->setParams([
            'email' => $this->_email,
            'passw' => $this->_password
        ]);

        foreach ($query->execute() as $record) {
            $info = [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_id' => $record['id'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ];

            $session = new Session($this->config->read('session_name') . '_sid');
            $session->create($this->_cookieLength, false, $this->config->read('cookie_id') . '_sid', $info);
            return;
        }

        $errors[] = $this->locale->read('auth', 'email_or_password_not_found');
    }

    public function setSessionUID() : void {
        if (empty($_SESSION[$this->config->read('session_name') . '_sid']))
            return;

        $session = $_SESSION[$this->config->read('session_name') . '_sid'];

        // Note: if one or more of these conditions is true, we should report it to the admin, since we'd be dealing with a security breach (session forgery)
        if ($session['ip'] != $_SERVER['REMOTE_ADDR'] || $session['user_agent'] != $_SERVER['HTTP_USER_AGENT'])
            return;

        else
            $this->_sessionUID = $session['user_id'];
    }

    public function logout() : void {
        $session = new Session($this->config->read('session_name') . '_sid');
        $session->destroy($this->config->read('cookie_id'));
    }

    public function hasPermission(string $key) : bool {
        return false;
    }

    public function isGuest() : bool {
        return $this->_id == 0;
    }

    public function getID() : int {
        return $this->_id;
    }

    public function getUsername() : string {
        return $this->_username;
    }

    public function getFirstName() : string {
        return $this->_firstName;
    }

    public function getLastName() : string {
        return $this->_lastName;
    }

    public function getEmail() : string {
        return $this->_email;
    }

    public function getPrimaryGroup() : int {
        return $this->_primaryGroup;
    }

    public function getGroups() : array {
        return $this->_groups;
    }

    public function getSex() : int {
        return $this->_sex;
    }

    public function getBirthdate() : string {
        return $this->_birthdate;
    }

    public function getIP() : int {
        return $this->_ip;
    }
}