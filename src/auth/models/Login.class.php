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
use DraiWiki\src\core\models\{InputValidator, PostRequest, Sanitizer};
use DraiWiki\src\main\models\ModelHeader;

class Login extends ModelHeader {

    private $_userInfo;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->locale->loadFile('auth');

        $this->_userInfo = [];
    }

    public function prepareData() : array {
        return [
            'max_email_length' => $this->config->read('max_email_length'),
            'max_password_length' => $this->config->read('max_password_length'),
            'action' => $this->config->read('url') . '/index.php/login'
        ];
    }

    public function getTitle() : string {
        return $this->locale->read('auth', 'logging_in');
    }

    public function handlePostRequest() : void {
        if (empty($_POST))
            return;

        $this->_userInfo['email'] = [
            'validator' => new InputValidator($_POST['email'] ?? ''),
            'value' => $_POST['email'] ?? ''
        ];

        $this->_userInfo['password'] = [
            'value' => (new PostRequest($_POST['password']))->getHash(),
            'validator' => new InputValidator($_POST['password'])
        ];
    }

    public function validate(array &$errors) : void {
        foreach ($this->_userInfo as $key => $field) {
            if ($field['validator']->isTooShort($minLength = $this->config->read('min_' . $key . '_length')))
                $errors[$key] = sprintf($this->locale->read('auth', $key . '_too_short'), $minLength);
            else if ($field['validator']->isTooLong($maxLength = $this->config->read('max_' . $key . '_length')))
                $errors[$key] = sprintf($this->locale->read('auth', $key . '_too_long'), $maxLength);
        }

        if (empty($errors['email']) && !$this->_userInfo['email']['validator']->isValidEmail())
            $errors['email'] = $this->locale->read('auth', 'invalid_email');

        if (empty($errors['password']) && $this->_userInfo['password']['validator']->containsSpaces())
            $errors['password'] = $this->locale->read('auth', 'password_no_spaces');
    }

    public function getUserInfo() : array {
        return [
            'email_address' => $this->_userInfo['email']['value'],
            'password' => $this->_userInfo['password']['value']
        ];
    }
}