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

use DraiWiki\src\core\models\{InputValidator, PostRequest};
use DraiWiki\src\main\models\{ModelHeader, Table};

class AccountManager extends ModelHeader {

    private $_user, $_table, $_fields;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->loadUser();

        self::$locale->loadFile('auth');
        self::$locale->loadFile('error');
    }

    public function loadAccount(?int $userID) : ?string {
        $this->_user = self::$user->getID() == $userID || empty($userID) ? self::$user : new User($userID);

        if ($this->_user->isGuest())
            return 'user_not_found';
        else if (!self::$user->hasPermission('manage_site') && self::$user->getID() != $this->_user->getID())
            return 'access_denied';

        $this->generateFields();
        return null;
    }

    public function prepareData() : array {
        $this->generateTable();

        return [
            'user' => $this->_user,
            'action' => self::$config->read('url') . '/index.php/account/settings/' . $this->_user->getID(),
            'table' => $this->_table
        ];
    }

    public function getTitle() : string {
        return self::$locale->read('auth', 'edit_settings');
    }

    public function generateFields() : void {
        $fields = [
            'account_configuration',
            'username' => [
                'name' => 'username',
                'label' => 'username',
                'description' => 'username_desc',
                'input_type' => 'text',
                'min_length' => self::$config->read('min_username_length'),
                'max_length' => self::$config->read('max_username_length'),
                'value' => $this->_user->getUsername()
            ],
            'email' => [
                'name' => 'email_address',
                'label' => 'email_title',
                'description' => 'email_desc',
                'input_type' => 'text',
                'min_length' => self::$config->read('min_email_length'),
                'max_length' => self::$config->read('max_email_length'),
                'value' => $this->_user->getEmail(),
                'format' => 'email',
            ],
            'is_activated' => [
                'name' => 'activated',
                'label' => 'activated',
                'description' => 'activated_desc',
                'input_type' => 'checkbox',
                'input_description' => self::$locale->read('auth', 'activate_account'),
                'value' => $this->_user->getIsActivated(),
                'visible' => $this->_user->getID() != self::$user->getID()
            ],
            'personal_information',
            'first_name' => [
                'name' => 'first_name',
                'label' => 'first_name',
                'description' => 'first_name_desc',
                'input_type' => 'text',
                'min_length' => self::$config->read('min_first_name_length'),
                'max_length' => self::$config->read('max_first_name_length'),
                'value' => $this->_user->getFirstName()
            ],
            'last_name' => [
                'name' => 'last_name',
                'label' => 'last_name',
                'description' => 'last_name_desc',
                'input_type' => 'text',
                'min_length' => self::$config->read('min_first_name_length'),
                'max_length' => self::$config->read('max_last_name_length'),
                'value' => $this->_user->getLastName()
            ],
            'sex' => [
                'name' => 'sex',
                'label' => 'sex',
                'description' => 'sex_desc',
                'input_type' => 'select',
                'options' => [
                    'unspecified' => [
                        'label' => 'sex_0',
                        'value' => 0
                    ],
                    'male' => [
                        'label' => 'sex_1',
                        'value' => 1
                    ],
                    'female' => [
                        'label' => 'sex_2',
                        'value' => 2,
                    ],
                    'other' => [
                        'label' => 'sex_3',
                        'value' => 3
                    ]
                ],
                'selected' => $this->_user->getSex()
            ]
        ];

        $this->_fields = [];
        foreach ($fields as &$field) {
            if (isset($field['visible']) && !$field['visible'])
                continue;

            if (!is_array($field)) {
                $this->_fields[] = ['label' => self::$locale->read('auth', $field), 'is_header' => true];
                continue;
            }

            $field['label'] = self::$locale->read('auth', $field['label']);
            $field['description'] = self::$locale->read('auth', $field['description']);

            if ($field['input_type'] == 'select') {
                foreach ($field['options'] as &$option) {
                    $option['label'] = self::$locale->read('auth', $option['label']);

                    if ($option['value'] == $field['selected'])
                        $option['selected'] = true;
                }
            }

            $this->_fields[] = $field;
        }
    }

    public function generateTable() : void {
        $columns = [
            'key',
            'value'
        ];

        $table = new Table('auth', $columns, $this->_fields);
        $table->setType('form');

        $table->create();
        $this->_table = $table->returnTable();
    }

    public function validateRequest(array &$errors) : void {
        foreach ($this->_fields as &$field) {
            if ((!empty($field['visible']) && !$field['visible']) || count($field) == 2)
                continue;

            $request = new PostRequest($field['name']);

            if ($field['input_type'] == 'checkbox') {
                $newValue = $request->getIsEmpty() ? 0 : 1;
                $field['updated'] = $newValue != $field['value'];
                $field['value'] = $newValue;
                continue;
            }

            if (($request->getIsEmpty() && empty($field['optional']) && $field['input_type'] != 'select') || (!$request->getIsset() && empty($field['optional']))) {
                $errors[$field['name']] = self::$locale->read('auth', 'field_empty_' . $field['name']);
                continue;
            }

            $validator = new InputValidator($_POST[$field['name']]);

            if ($validator->getValue() == ($field['value'] ?? $field['selected'])) {
                $field['updated'] = false;
                continue;
            }
            else
                $field['updated'] = true;

            if (!empty($field['format'])) {
                switch ($field['format']) {
                    case 'email':
                        if (!$validator->isValidEmail()) {
                            $errors['email'] = self::$locale->read('auth', 'invalid_email');
                            continue;
                        }
                        break;
                }
            }

            if ($field['input_type'] == 'select') {
                $found = false;
                foreach ($field['options'] as $option) {
                    if ($validator->getValue() == $option['value']) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $errors[$field['name']] = self::$locale->read('auth', 'select_value_does_not_exist');
                    continue;
                }
            }

            if ((substr($validator->getValue(), 0) == ' ' || substr($validator->getValue(), -1) == ' ')) {
                $errors[$field['name']] = self::$locale->read('auth', 'oh_god_begin_end_spaces_' . $field['name']);
                continue;
            }

            if ($validator->containsHTML()) {
                $errors[$field['name']] = self::$locale->read('auth', 'html_nope');
                continue;
            }

            if (!empty(self::$config->read('min_' . $field['name'] . '_length')) && !empty(self::$config->read('max_' . $field['name'] . '_length'))) {
                if ($validator->isTooShort($minLength = self::$config->read('min_' . $field['name'] . '_length')))
                    $errors[$field['name']] = sprintf(self::$locale->read('auth', $field['name'] . '_too_short'), $minLength);
                else if ($validator->isTooLong($maxLength = self::$config->read('max_' . $field['name'] . '_length')))
                    $errors[$field['name']] = sprintf(self::$locale->read('auth', $field['name'] . '_too_long'), $maxLength);
            }

            if (empty($errors[$field['name']])) {
                if ($field['input_type'] == 'select') {
                    foreach ($field['options'] as &$option) {
                        if ($option['value'] == $validator->getValue()) {
                            $option['selected'] = true;
                            continue;
                        }
                        else
                            $option['selected'] = false;
                    }
                }

                $field['value'] = $validator->getValue();
            }
        }
    }

    public function updateUser(array &$errors) : void {
        $updateSettings = [];

        foreach ($this->_fields as $field) {
            if ((!empty($field['visible']) && !$field['visible']) || count($field) == 2)
                continue;

            if ($field['updated'])
                $updateSettings[$field['name']] = $field['value'];
        }

        $this->_user->update($updateSettings, $errors);
    }
}