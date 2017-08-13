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
use DraiWiki\src\core\models\{InputValidator, PostRequest};
use DraiWiki\src\main\models\ModelHeader;
use Parsedown;

class Registration extends ModelHeader {

    private $_agreement, $_parsedown, $_formData;

    private const DEFAULT_LOCALE_ID = 1;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        self::$locale->loadFile('auth');

        $this->_parsedown = new Parsedown();
        $this->getAgreement();

        $this->_formData = [];
    }

    private function getAgreement() : void {
        $query = QueryFactory::produce('select', '
            SELECT body
                FROM {db_prefix}agreement
                WHERE locale_id = :locale
                OR locale_id = :default_locale
        ');

        $query->setParams([
            'locale' => self::$locale->getID(),
            'default_locale' => self::DEFAULT_LOCALE_ID
        ]);

        foreach ($query->execute() as $agreement) {
            if (!empty(trim($agreement['body']))) {
                $this->_agreement = $this->_parsedown->setMarkupEscaped(true)->text($agreement['body']);
                return;
            }
        }

        $this->_agreement = self::$locale->read('auth', 'no_agreement_found');
    }

    public function prepareData(): array {
        return [
            'action' => self::$config->read('url') . '/index.php/register',
            'agreement' => $this->_agreement,
            'max_username_length' => self::$config->read('max_username_length'),
            'max_password_length' => self::$config->read('max_password_length'),
            'max_email_length' => self::$config->read('max_email_length'),
            'max_first_name_length' => self::$config->read('max_first_name_length'),
            'max_last_name_length' => self::$config->read('max_last_name_length')
        ];
    }

    public function getTitle() : string {
        return self::$locale->read('auth', 'create_account');
    }

    public function handlePostRequest() : void {
        if (empty($_POST))
            return;

        foreach (['username', 'password', 'confirm_password', 'email', 'first_name', 'last_name'] as $field) {
            $this->_formData[$field] = [
                'request' => new PostRequest($field),
                'validator' => new InputValidator($_POST[$field] ?? ''),
                'value' => $_POST[$field] ?? ''
            ];
        }
    }

    /**
     * Check user input. Since the code looks a lot cleaner if we do this dynamically, we're using a loop here.
     * @param array $errors
     */
    public function validate(array &$errors) : void {
        if (!isset($_POST['agreement_accept'])) {
            $errors[] = self::$locale->read('auth', 'please_accept');
            return;
        }

        foreach ($this->_formData as $key => $field) {
            if (!empty($field['value']) && (substr($field['value'], 0) == ' ' || substr($field['value'], -1) == ' ')) {
                $errors[$key] = self::$locale->read('auth', 'oh_god_begin_end_spaces_' . $key);
                continue;
            }

            if ($key != 'confirm_password' && $field['validator']->isTooShort($minLength = self::$config->read('min_' . $key . '_length')))
                $errors[$key] = sprintf(self::$locale->read('auth', $key . '_too_short'), $minLength);
            else if ($key != 'confirm_password' && $field['validator']->isTooLong($maxLength = self::$config->read('max_' . $key . '_length')))
                $errors[$key] = sprintf(self::$locale->read('auth', $key . '_too_long'), $maxLength);

            if ($key != 'confirm_password' && $field['validator']->containsHTML())
                $errors[$key] = self::$locale->read('auth', 'no_html_' . $key);
        }

        if (count($errors) == 0 && $this->_formData['password']['value'] != $this->_formData['confirm_password']['value'])
            $errors['confirm_password'] = self::$locale->read('auth', 'no_password_match');

        if (empty($errors['password']) && $this->_formData['password']['validator']->containsSpaces())
            $errors['password'] = self::$locale->read('auth', 'password_no_spaces');

        if (empty($errors['email']) && !$this->_formData['email']['validator']->isValidEmail())
            $errors['email'] = self::$locale->read('auth', 'invalid_email');
    }

    public function createUser(array &$errors) : void {
        $userInfo = [
            'username' => $this->_formData['username']['value'],
            'password' => $this->_formData['password']['request']->getHash(),
            'email_address' => $this->_formData['email']['value'],
            'first_name' => $this->_formData['first_name']['value'],
            'last_name' => $this->_formData['last_name']['value'],
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        $user = new User(null, $userInfo);
        $user->create($errors);
    }

    public function getRegistrationDisabledTitle() : string {
        return self::$locale->read('auth', 'registration_disabled_title');
    }
}