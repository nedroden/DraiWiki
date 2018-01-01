<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\ModelHeader;

class Management extends ModelHeader {

    private $_sidebar, $_title, $_activeMenuItem;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->loadUser();

        self::$locale->loadFile('management');
        $this->_title = self::$locale->read('management', 'management_panel');
    }

    public function prepareData(): array {
        return [
            'sidebar' => $this->_sidebar,
            'title' => $this->_title,
            'locale' => self::$locale,
            'node_url' => self::$config->read('url') . '/node_modules',
            'script_url' => self::$config->read('url') . '/scripts',
            'wiki_version' => Main::WIKI_VERSION,
            'user' => self::$user
        ];
    }

    public function generateSidebar() : void {
        $items = [
            'main' => [
                'label' => 'side_main',
                'visible' => true,
                'items' => [
                    'home' => [
                        'label' => 'home',
                        'icon' => 'fa-home',
                        'href' => self::$config->read('url') . '/index.php/management',
                        'visible' => true
                    ],
                    'wiki' => [
                        'label' => 'back_to_wiki',
                        'icon' => 'fa-book',
                        'href' => self::$config->read('url') . '/index.php',
                        'visible' => true
                    ],
                    'logout' => [
                        'label' => 'logout',
                        'icon' => 'fa-sign-out',
                        'href' => self::$config->read('url') . '/index.php/logout',
                        'visible' => !self::$user->isGuest(),
                        'request_confirm' => true
                    ]
                ]
            ],
            'config' => [
                'label' => 'side_config',
                'visible' => true,
                'items' => [
                    'settings_general' => [
                        'label' => 'config_general',
                        'icon' => 'fa-wrench',
                        'href' => self::$config->read('url') . '/index.php/management/settings/general',
                        'visible' => true
                    ],
                    'settings_database' => [
                        'label' => 'config_database',
                        'icon' => 'fa-database',
                        'href' => self::$config->read('url') . '/index.php/management/databasesettings',
                        'visible' => true
                    ]
                ]
            ],
            'users' => [
                'label' => 'side_users',
                'visible' => true,
                'items' => [
                    'users' => [
                        'label' => 'display_users',
                        'icon' => 'fa-user',
                        'href' => self::$config->read('url') . '/index.php/management/users',
                        'visible' => true
                    ],
                    'groups' => [
                        'label' => 'manage_groups',
                        'icon' => 'fa-users',
                        'href' => self::$config->read('url') . '/index.php/management/groups',
                        'visible' => true
                    ],
                    'settings_registration' => [
                        'label' => 'registration',
                        'icon' => 'fa-pencil',
                        'href' => self::$config->read('url') . '/index.php/management/settings/registration',
                        'visible' => true
                    ],
                ]
            ],
            'security' => [
                'label' => 'side_security',
                'visible' => true,
                'items' => [
                    'permissions' => [
                        'label' => 'permissions',
                        'icon' => 'fa-check',
                        'href' => self::$config->read('url') . '/index.php/management/permissions',
                        'visible' => true
                    ],
                    'bans' => [
                        'label' => 'bans',
                        'icon' => 'fa-ban',
                        'href' => self::$config->read('url') . '/index.php/management/banlist',
                        'visible' => true
                    ],
                    'log' => [
                        'label' => 'security_log',
                        'icon' => 'fa-file-text',
                        'href' => self::$config->read('url') . '/index.php/management/securitylog',
                        'visible' => true
                    ]
                ]
            ],
            'site_and_file_maintenance' => [
                'label' => 'site_and_file_maintenance',
                'visible' => true,
                'items' => [
                    'generalmaintenance' => [
                        'label' => 'maintenance_actions',
                        'icon' => 'fa-line-chart',
                        'href' => self::$config->read('url') . '/index.php/management/generalmaintenance',
                        'visible' => true
                    ],
                    'manageuploads' => [
                        'label' => 'manage_uploads',
                        'icon' => 'fa-upload',
                        'href' => self::$config->read('url') . '/index.php/management/manageuploads',
                        'visible' => true
                    ],
                ]
            ],
            'extend' => [
                'label' => 'side_extend',
                'visible' => true,
                'items' => [
                    'locales' => [
                        'label' => 'locale_management',
                        'icon' => 'fa-language',
                        'href' => self::$config->read('url') . '/index.php/management/locales',
                        'visible' => true
                    ],
                    'themes' => [
                        'label' => 'theme_management',
                        'icon' => 'fa-paint-brush',
                        'href' => self::$config->read('url') . '/index.php/management/themes',
                        'visible' => true
                    ]
                ]
            ],
            'help' => [
                'label' => 'side_help',
                'visible' => true,
                'items' => [
                    'sysinfo' => [
                        'label' => 'detailed_system_information',
                        'icon' => 'fa-info',
                        'href' => self::$config->read('url') . '/index.php/management/sysinfo',
                        'visible' => true
                    ],
                    'manual' => [
                        'label' => 'manual',
                        'icon' => 'fa-question',
                        'href' => self::$config->read('url') . '/index.php/management/manual',
                        'visible' => true
                    ]
                ]
            ]
        ];

        $visibleTabs = [];

        foreach ($items as $key => $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                $item['label'] = self::$locale->read('management', $item['label']);

                $visibleSubItems = [];
                foreach ($item['items'] as $subItemKey => $subItem) {
                    $subItem['label'] = self::$locale->read('management', $subItem['label']);

                    if ($subItem['visible'])
                        $visibleSubItems[$subItemKey] = $subItem;
                }

                if (empty($activeTabFound) && !empty($visibleSubItems[$this->_activeMenuItem])) {
                    $item['has_active'] = true;
                    $visibleSubItems[$this->_activeMenuItem]['active'] = true;
                    $activeTabFound = true;
                }

                // Only display items we can see
                $item['items'] = $visibleSubItems;
                $item['key'] = $key;

                $visibleTabs[$key] = $item;
            }
        }

        if (empty($activeTabFound)) {
            $visibleTabs['main']['has_active'] = true;
            $visibleTabs['main']['items']['home']['active'] = true;
        }

        $this->_sidebar = $visibleTabs;
    }

    public function getSidebar() : array {
        return $this->_sidebar;
    }

    public function setTitle(string $title) : void {
        $this->_title = $title;
    }

    public function setActiveMenuItem(string $item) : void {
        $this->_activeMenuItem = $item;
    }
}