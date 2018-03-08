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

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\{InputValidator, PostRequest};
use DraiWiki\src\main\controllers\GUI;
use DraiWiki\src\main\models\{ModelHeader, Table};

class SettingsPage extends ModelHeader {

    private $_validSettings, $_table, $_settings, $_settingsSection, $_action;

    private const MIN_WIKI_TITLE_LENGTH = 3;
    private const MIN_WIKI_SLOGAN_LENGTH = 3;
    private const MIN_WIKI_EMAIL_LENGTH = 8;
    private const MIN_DATE_FORMAT_LENGTH = 4;

    private const MAX_WIKI_TITLE_LENGTH = 30;
    private const MAX_WIKI_SLOGAN_LENGTH = 100;
    private const MAX_WIKI_EMAIL_LENGTH = 40;
    private const MAX_DATE_FORMAT_LENGTH = 30;

    public function __construct(string $section) {
        $this->loadConfig();

        $this->_settingsSection = $section;
    }

    public function prepareData() : array {
        return [
            'table' => $this->_table,
            'action' => $this->_action ?? self::$config->read('url') . '/index.php/management/settings/' . $this->_settingsSection
        ];
    }

    public function generateTable() : void {
        $columns = [
            'key',
            'value'
        ];

        $table = new Table('management', $columns, $this->_settings);
        $table->setType('form');

        $table->create();
        $this->_table = $table->returnTable();
    }

    public function loadSettings() : void {
        $settings = [
            'general' => [
                'basic_settings',
                'wiki_name' => [
                    'name' => 'wiki_name',
                    'label' => 'wiki_name',
                    'description' => 'wiki_name_desc',
                    'input_type' => 'text',
                    'min_length' => self::MIN_WIKI_TITLE_LENGTH,
                    'max_length' => self::MAX_WIKI_TITLE_LENGTH
                ],
                'wiki_slogan' => [
                    'name' => 'slogan',
                    'label' => 'wiki_slogan',
                    'description' => 'wiki_slogan_desc',
                    'input_type' => 'text',
                    'min_length' => self::MIN_WIKI_SLOGAN_LENGTH,
                    'max_length' => self::MAX_WIKI_SLOGAN_LENGTH,
                    'optional' => true
                ],
                'wiki_email' => [
                    'name' => 'wiki_email',
                    'label' => 'wiki_email',
                    'description' => 'wiki_email_desc',
                    'input_type' => 'text',
                    'min_length' => self::MIN_WIKI_EMAIL_LENGTH,
                    'max_length' => self::MAX_WIKI_EMAIL_LENGTH,
                    'type' => 'email'
                ],
                'features',
                'display_cookie_warning' => [
                    'name' => 'display_cookie_warning',
                    'label' => 'display_cookie_warning',
                    'description' => 'display_cookie_warning_desc',
                    'input_type' => 'checkbox',
                    'input_description' => _localized('management.enable_this_feature')
                ],
                'date_format' => [
                    'name' => 'date_format',
                    'label' => 'date_format',
                    'description' => 'date_format_desc',
                    'input_type' => 'text',
                    'min_length' => self::MIN_DATE_FORMAT_LENGTH,
                    'max_length' => self::MAX_DATE_FORMAT_LENGTH
                ],
                'datetime_format' => [
                    'name' => 'datetime_format',
                    'label' => 'datetime_format',
                    'description' => 'datetime_format_desc',
                    'input_type' => 'text',
                    'min_length' => self::MIN_DATE_FORMAT_LENGTH,
                    'max_length' => self::MAX_DATE_FORMAT_LENGTH
                ],
                'use_first_name_greeting' => [
                    'name' => 'use_first_name_greeting',
                    'label' => 'use_first_name_greeting',
                    'description' => 'use_first_name_greeting_desc',
                    'input_type' => 'checkbox',
                    'input_description' => _localized('management.enable_this_feature')
                ],
                'paths_and_urls',
                'path' => [
                    'name' => 'path',
                    'label' => 'base_path',
                    'description' => 'base_path_desc',
                    'input_type' => 'text',
                    'min_length' => 1,
                    'max_length' => 0
                ],
                'url' => [
                    'name' => 'url',
                    'label' => 'base_url',
                    'description' => 'base_url_desc',
                    'input_type' => 'text',
                    'min_length' => 1,
                    'max_length' => 0
                ],
                'cookies_and_sessions',
                'cookie_id' => [
                    'name' => 'cookie_id',
                    'label' => 'cookie_id',
                    'description' => 'cookie_id_desc',
                    'input_type' => 'text',
                    'min_length' => 1,
                    'max_length' => 0
                ],
                'session_name' => [
                    'name' => 'session_name',
                    'label' => 'session_name',
                    'description' => 'session_name_desc',
                    'input_type' => 'text',
                    'min_length' => 1,
                    'max_length' => 0
                ],
                'theme_settings',
                'templates' => [
                    'name' => 'templates',
                    'label' => 'template_set',
                    'description' => 'template_set_desc',
                    'input_type' => 'select',
                    'options' => applyToAll(GUI::getThemeDirectories('templates', 'header.tpl'), function(string &$element) {
                        $element = [
                            'label' => $element,
                            'value' => $element
                        ];
                    }),
                    'selected' => self::$config->read('templates')
                ],
                'images' => [
                    'name' => 'images',
                    'label' => 'image_set',
                    'description' => 'image_set_desc',
                    'input_type' => 'select',
                    'options' => applyToAll(GUI::getThemeDirectories('images', 'index.php'), function(string &$element) {
                        $element = [
                            'label' => $element,
                            'value' => $element
                        ];
                    }),
                    'selected' => self::$config->read('images')
                ],
                'skins' => [
                    'name' => 'skins',
                    'label' => 'skin_set',
                    'description' => 'skin_set_desc',
                    'input_type' => 'select',
                    'options' => applyToAll(GUI::getThemeDirectories('skins', 'main.css'), function(string &$element) {
                        $element = [
                            'label' => $element,
                            'value' => $element
                        ];
                    }),
                    'selected' => self::$config->read('skins')
                ]
            ],
            'registration' => [
                'general_registration_settings',
                'enable_registration' => [
                    'name' => 'enable_registration',
                    'label' => 'enable_registration',
                    'description' => 'enable_registration_desc',
                    'input_type' => 'checkbox',
                    'input_description' => _localized('management.enable_this_feature')
                ],
                'enable_email_activation' => [
                    'name' => 'enable_email_activation',
                    'label' => 'enable_email_activation',
                    'description' => 'enable_email_activation_desc',
                    'input_type' => 'checkbox',
                    'input_description' => _localized('management.enable_this_feature')
                ]
            ],
            'uploads' => [
                'image_uploading',
                'gd_image_upload' => [
                    'name' => 'gd_image_upload',
                    'label' => 'gd_image_upload',
                    'description' => 'gd_image_upload_desc',
                    'input_type' => 'checkbox',
                    'input_description' => _localized('management.enable_this_feature')
                ],
            ]
        ];

        foreach ($settings[$this->_settingsSection] as &$setting) {
            if (!is_array($setting)) {
                $setting = ['label' => _localized('management.' . $setting), 'is_header' => true];
                continue;
            }

            $setting['label'] = _localized('management.' . $setting['label']);
            $setting['description'] = _localized('management.' . $setting['description']);

            // If this field was entered correctly, we should remember it
            if (isset($this->_validSettings[$setting['name']]))
                $setting['value'] = $this->_validSettings[$settings['name']];
            else
                $setting['value'] = self::$config->read($setting['name']);

            if ($setting['input_type'] == 'select') {
                foreach ($setting['options'] as &$option) {
                    if ($option['value'] == $setting['selected'])
                        $option['selected'] = true;
                }
            }
        }

        $this->_settings = $settings[$this->_settingsSection];
    }

    public function validateSettings(array &$errors) : void {
        foreach ($this->_settings as &$setting) {
            // If there are only two items, it means we're dealing with a header
            if (count($setting) == 2)
                continue;

            if ($setting['input_type'] != 'checkbox' && $setting['input_type'] != 'select') {
                $settingInfo = [
                    'request' => new PostRequest($setting['name']),
                    'validator' => new InputValidator($_POST[$setting['name']] ?? ''),
                    'value' => $_POST[$setting['name']] ?? ''
                ];

                if ($settingInfo['request']->getIsEmpty() && !empty($setting['optional']) && $setting['optional']) {
                    $setting['value'] = '';
                    continue;
                }

                if ($settingInfo['validator']->isTooShort($setting['min_length']))
                    $errors[$setting['name']] = sprintf(_localized('management.' . $setting['name'] . '_too_short'), $setting['min_length']);
                else if ($settingInfo['validator']->isTooLong($setting['max_length']))
                    $errors[$setting['name']] = sprintf(_localized('management.' . $setting['name'] . '_too_long'), $setting['max_length']);

                if (empty($errors[$setting['name']]) && !empty($setting['type'])) {
                    switch ($setting['type']) {
                        case 'email':
                            if (!$settingInfo['validator']->isValidEmail())
                                $errors[$setting['name']] = _localized('management.invalid_email');
                            break;
                        case 'numeric':
                            if (!$settingInfo['validator']->isNumeric())
                                $errors[$setting['name']] = _localized('management.not_numeric' . $setting['name']);
                            else if (!$settingInfo['validator']->aboveIntLimit())
                                $errors[$setting['name']] = _localized('management.above_int_limit');
                            break;
                    }
                }

                // If there's nothing wrong with the specified value for a setting, we might as well use it in the form
                if (empty($errors[$setting['name']])) {
                    if ($setting['value'] != $settingInfo['value'])
                        $setting['updated'] = true;

                    $setting['value'] = $settingInfo['value'];
                }
            }

            else if ($setting['input_type'] == 'checkbox') {
                $settingValue = isset($_POST[$setting['name']]) ? 1 : 0;

                if ($settingValue != $setting['value'])
                    $setting['updated'] = true;

                $setting['value'] = $settingValue;
            }

            else if ($setting['input_type'] == 'select') {
                $request = new PostRequest($setting['name']);

                if ($request->getIsEmpty()) {
                    $errors[$setting['name']] = _localized('error.input_empty', $setting['name']);
                    continue;
                }

                $found = false;
                foreach ($setting['options'] as $option) {
                    if ($request->getValue() == $option['value']) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $errors[$setting['name']] = _localized('error.select_value_does_not_exist');
                    continue;
                }

                if (empty($errors[$setting['name']])) {
                    if ($setting['value'] != $request->getValue())
                        $setting['updated'] = true;

                    $setting['selected'] = $request->getValue();
                    $setting['value'] = $setting['selected'];
                }
            }
        }
    }

    public function updateSettings() : void {
        foreach ($this->_settings as $setting) {
            // If there are only two items, it means we're dealing with a header
            if (count($setting) == 2 || (!empty($setting['updated']) && !$setting['updated']))
                continue;

            $query = QueryFactory::produce('modify', '
                UPDATE {db_prefix}setting SET `value` = :val WHERE `key` = :key
            ');

            $query->setParams([
                'val' => $setting['value'],
                'key' => $setting['name']
            ]);

            $query->execute();
        }
    }

    public function getTitle() : string {
        return _localized('management.settings_' . $this->_settingsSection);
    }

    public function getPageDescription() : string {
        return _localized('management.settings_' . $this->_settingsSection . '_description');
    }

    public function setAction(string $action) : void {
        $this->_action = $action;
    }
}