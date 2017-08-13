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

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\{InputValidator, PostRequest};
use DraiWiki\src\main\models\{ModelHeader, Table};

class SettingsPage extends ModelHeader {

    private $_validSettings, $_table, $_settings, $_settingsSection;

    private const MIN_WIKI_TITLE_LENGTH = 3;
    private const MIN_WIKI_SLOGAN_LENGTH = 3;
    private const MIN_WIKI_EMAIL_LENGTH = 8;

    private const MAX_WIKI_TITLE_LENGTH = 30;
    private const MAX_WIKI_SLOGAN_LENGTH = 100;
    private const MAX_WIKI_EMAIL_LENGTH = 40;

    public function __construct(string $section) {
        $this->loadLocale();
        $this->loadConfig();

        $this->_settingsSection = $section;
    }

    public function prepareData(): array {
        return [
            'table' => $this->_table,
            'action' => self::$config->read('url') . '/index.php/management/settings/' . $this->_settingsSection
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

    public function loadSettings(): void {
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
                    'input_description' => self::$locale->read('management', 'enable_this_feature')
                ],
            ]
        ];

        foreach ($settings[$this->_settingsSection] as &$setting) {
            if (!is_array($setting)) {
                $setting = ['label' => self::$locale->read('management', $setting), 'is_header' => true];
                continue;
            }

            $setting['label'] = self::$locale->read('management', $setting['label']);
            $setting['description'] = self::$locale->read('management', $setting['description']);

            if (isset($this->_validSettings[$setting['name']]))
                $setting['value'] = $this->_validSettings[$settings['name']];
            else
                $setting['value'] = self::$config->read($setting['name']);
        }

        $this->_settings = $settings[$this->_settingsSection];
    }

    public function validateSettings(array &$errors): void {
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
                    $errors[$setting['name']] = sprintf(self::$locale->read('management', $setting['name'] . '_too_short'), $setting['min_length']);
                else if ($settingInfo['validator']->isTooLong($setting['max_length']))
                    $errors[$setting['name']] = sprintf(self::$locale->read('management', $setting['name'] . '_too_long'), $setting['max_length']);

                if (empty($errors[$setting['name']]) && !empty($setting['type'])) {
                    switch ($setting['type']) {
                        case 'email':
                            if (!$settingInfo['validator']->isValidEmail())
                                $errors[$setting['name']] = self::$locale->read('management', 'invalid_email');
                            break;
                        case 'numeric':
                            if (!$settingInfo['validator']->isNumeric())
                                $errors[$setting['name']] = self::$locale->read('management', 'not_numeric' . $setting['name']);
                            else if (!$settingInfo['validator']->aboveIntLimit())
                                $errors[$setting['name']] = self::$locale->read('management', 'above_int_limit');
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

            else {
                $settingValue = isset($_POST[$setting['name']]) ? 1 : 0;

                if ($settingValue != $setting['value'])
                    $setting['updated'] = true;

                $setting['value'] = $settingValue;
            }
        }
    }

    public function updateSettings(): void {
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
        return self::$locale->read('management', 'settings_' . $this->_settingsSection);
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'settings_' . $this->_settingsSection . '_description');
    }
}