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
use DraiWiki\src\main\models\ModelHeader;

class User extends ModelHeader {

    // Note: passwords are hashed before they arrive here
    private $_id, $_username, $_password, $_firstName, $_lastName;
    private $_email, $_primaryGroup, $_groups;
    private $_sex, $_birthdate, $_ip;

    public function __construct(int $specificUser = null, array $details = []) {
        $this->loadConfig();
        $this->loadLocale();

        $this->locale->loadFile('auth');

        // If we're dealing with a registration we shouldn't attempt to load user information from the database
        if (empty($details) && empty($specificUser))
            $this->load();
        else if (empty($details) && !empty($specificUser))
            $this->load($specificUser);
        else
            $this->setUserInfo($details);
    }

    private function load(int $specificUser = null) : void {

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
    }

    public function create(array &$errors) : void {
        if (count($errors) == 0)
            $errors = array_merge($errors, $this->validate());

        if (count($errors) > 0)
            return;

        $query = QueryFactory::produce('modify', '
            INSERT
                INTO {db_prefix}user (
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
}