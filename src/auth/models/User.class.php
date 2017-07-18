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
use DraiWiki\src\core\models\LogEntry;
use DraiWiki\src\core\models\Session;
use DraiWiki\src\main\models\ModelHeader;
use SimpleMail;

class User extends ModelHeader {

    // Note: passwords are hashed before they arrive here
    private $_id, $_username, $_password, $_firstName, $_lastName;
    private $_email, $_primaryGroup, $_groups, $_isActivated;
    private $_sex, $_birthdate, $_ip, $_cookieLength, $_registrationDate;

    private $_primaryGroupInfo;

    private $_sessionUID;
    private $_isRoot;

    private $_permissions;

    public function __construct(int $specificUser = null, array $details = []) {
        $this->loadConfig();
        $this->loadLocale();

        $this->locale->loadFile('auth');
        $this->locale->loadFile('error');
        $this->_sessionUID = 0;

        $this->setSessionUID();

        $this->_permissions = [];
        $this->_isRoot = false;

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
            SELECT u.id, u.username, u.email_address, u.sex, u.birthdate, u.first_name, u.last_name, 
                    u.ip_address, u.registration_date, u.group_id, u.secondary_groups, DATE(u.registration_date) AS registration_date,
                    g.title, g.color
                FROM `{db_prefix}user` u
                INNER JOIN `{db_prefix}group` g ON (g.id = u.group_id)
                WHERE u.id = :uid
        ');

        $query->setParams([
            'uid' => $uid
        ]);

        foreach ($query->execute() as $user) {
            $this->setUserInfo($user);
            $this->loadPermissions();
            return;
        }

        $this->setUserInfo([
            'group_id' => 5
        ]);

        $this->loadPermissions();
    }

    private function loadPermissions() : void {
        // If we're dealing with a root account, there's no need to load permissions
        if (in_array(1, $this->_groups)) {
            $this->_isRoot = true;
            return;
        }

        // Load the permissions for each group the user belongs to
        $query = QueryFactory::produce('select', '
            SELECT p.permissions
                FROM `{db_prefix}group` g
                INNER JOIN {db_prefix}permission_group p ON (g.permission_group_id = p.id)
                WHERE g.id IN (:ids)
        ');

        $query->setParams(['ids' => implode(',', $this->_groups)]);
        $result = $query->execute();

        $deniedPermissions = [];

        foreach ($result as $profile) {
            if (empty($profile['permissions']))
                continue;

            $permissions = explode(';', $profile['permissions']);

            foreach ($permissions as $permission) {
                $currentPermission = explode(':', $permission);

                if ($currentPermission[1] == 'a')
                    $this->_permissions[] = $currentPermission[0];
                else
                    $deniedPermissions[] = $currentPermission[0];
            }
        }

        // Get rid of the permissions we shouldn't have
        foreach ($deniedPermissions as $deniedPermission) {
            if (isset($this->_permissions[$deniedPermission]))
                unset($this->_permissions[$deniedPermission]);
        }
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

        if (!empty($details['secondary_groups']) && !is_array($details['secondary_groups']))
            $details['secondary_groups'] = [$details['secondary_groups']];

        // We're dealing with a new user here, so we don't have any secondary groups
        if (empty($details['group_id']))
            $this->_groups = [$this->_primaryGroup];
        else if (!empty($details['secondary_groups']))
            $this->_groups = array_merge([$details['group_id']], $details['secondary_groups']);
        else
            $this->_groups = [$details['group_id']];

        $this->_ip = $details['ip'] ?? '127.0.0.1';

        $this->_sex = $details['sex'] ?? 0;
        $this->_birthdate = $details['birthdate'] ?? '';
        $this->_registrationDate = $details['registration_date'] ?? '';
        $this->_email = $details['email_address'] ?? 'nobody@example.com';

        $this->_cookieLength = $details['cookie_length'] ?? 7 * 24 * 60 * 60;

        $this->_isActivated = $details['activated'] ?? $this->config->read('enable_email_activation') == 1 ? 0 : 1;

        $this->_primaryGroupInfo = [
            'title' => $details['title'] ?? '??',
            'color' => $details['color'] ?? '??'
        ];
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
                    ip_address, group_id, secondary_groups, activated
                )
                VALUES (
                    :username, :passw, :email, :sex, :birthdate,
                    :first_name, :last_name, :ip_address,
                    :group_id, :secondary_groups, :activated
                )
        ');

        // Don't include the primary user groups as a secondary group
        if (count($this->_groups) == 0 || (count($this->_groups) == 1 && $this->_groups[0] == $this->_primaryGroup))
            $groups = null;
        else {
            unset($this->_groups[0]);
            $this->_groups = count($this->_groups) >= 1 ? implode(', ', $this->_groups) : null;
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
            'secondary_groups' => $this->_groups,
            'activated' => $this->_isActivated
        ]);

        $query->execute();

        unset($this->_password);

        if ($this->config->read('enable_email_activation') == 1 && $this->_isActivated == 0)
            $this->sendVerificationMail();
    }

    private function sendVerificationMail() : void {
        $activationCode = new ActivationCode($this->_id);

        $replaceThis = [
            '{wiki_name}',
            '{first_name}',
            '{activation_code}',
            '{activation_link}'
        ];

        $replaceWith = [
            $this->config->read('wiki_name'),
            $this->_firstName,
            $activationCode->getCode(),
            $activationCode->getLink()
        ];

        $messageBody = str_replace($replaceThis, $replaceWith, $this->locale->read('auth', 'registration_mail_body'));

        $email = SimpleMail::make()
            ->setTo($this->_email, $this->_firstName . ' ' . $this->_lastName)
            ->setFrom($this->config->read('wiki_email'), $this->config->read('wiki_name'))
            ->setSubject(sprintf($this->locale->read('auth', 'registration_mail_title'), $this->config->read('wiki_name')))
            ->setMessage($messageBody)
            ->setHtml()
            ->send();

        if (!$email)
            (new LogEntry($this->locale->read('error', 'could_not_send_mail'), 'error', ['email' => $this->_email]))->create();
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

    /**
     * Activates the user's account. Note: it is up to the developer to make
     * sure a valid verification code was used. This function assumes it was.
     * @return void
     */
    public function activate() : void {
        $query = QueryFactory::produce('modify', '
            UPDATE `{db_prefix}user`
                SET activated = 1
                WHERE id = :uid
        ');

        $query->setParams(['uid' => $this->_id]);
        $query->execute();
    }

    public function hasPermission(string $key) : bool {
        if ($this->_isRoot)
            return true;

        return in_array($key, $this->_permissions);
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

    public function getRegistrationDate() : string {
        return $this->_registrationDate;
    }

    public function getPrimaryGroupInfo() : array {
        return $this->_primaryGroupInfo;
    }

    public function getPrimaryGroupWithColor() : string {
        return '<span style=\"color: #' .$this->_primaryGroupInfo['color'] . '\">' . $this->_primaryGroupInfo['title'] . '</span>';
    }
}