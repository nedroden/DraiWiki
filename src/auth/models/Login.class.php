<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\models\{InputValidator, PostRequest};
use DraiWiki\src\main\models\ModelHeader;

class Login extends ModelHeader {

    private $_userInfo;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        $this->_userInfo = [];
    }

    public function prepareData() : array {
        return [
            'max_email_length' => self::$config->read('max_email_length'),
            'max_password_length' => self::$config->read('max_password_length'),
            'action' => self::$config->read('url') . '/index.php/login'
        ];
    }

    public function getTitle() : string {
        return _localized('auth.logging_in');
    }

    public function handlePostRequest() : void {
        if (empty($_POST))
            return;

        $this->_userInfo['email'] = [
            'validator' => new InputValidator($_POST['email'] ?? ''),
            'value' => $_POST['email'] ?? ''
        ];

        if (!empty($_POST['password'])) {
            $this->_userInfo['password'] = [
                'validator' => new InputValidator($_POST['password'])
            ];
        }
    }

    public function validate(array &$errors) : void {
        if (empty($this->_userInfo['password'])) {
            $errors['password'] = _localized('auth.please_enter_password');
            return;
        }

        foreach ($this->_userInfo as $key => $field) {
            if ($field['validator']->isTooShort($minLength = self::$config->read('min_' . $key . '_length')))
                $errors[$key] = sprintf(_localized('auth.' . $key . '_too_short'), $minLength);
            else if ($field['validator']->isTooLong($maxLength = self::$config->read('max_' . $key . '_length')))
                $errors[$key] = sprintf(_localized('auth.' . $key . '_too_long'), $maxLength);
        }

        if (empty($errors['email']) && !$this->_userInfo['email']['validator']->isValidEmail())
            $errors['email'] = _localized('auth.invalid_email');

        if (empty($errors['password']) && $this->_userInfo['password']['validator']->containsSpaces())
            $errors['password'] = _localized('auth.password_no_spaces');
    }

    public function getUserInfo() : array {
        return [
            'email_address' => $this->_userInfo['email']['value'],
            'password' => $this->_userInfo['password']['validator']->getValue()
        ];
    }
}