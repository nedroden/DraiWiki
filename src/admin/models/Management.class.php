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

use DraiWiki\src\main\controllers\Main;
use DraiWiki\src\main\models\ModelHeader;

class Management extends ModelHeader {

    private $_sidebar, $_title;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->loadUser();

        $this->locale->loadFile('management');
        $this->_title = $this->locale->read('management', 'management_panel');
    }

    public function prepareData(): array {
        return [
            'sidebar' => $this->_sidebar,
            'title' => $this->_title,
            'locale' => $this->locale,
            'node_url' => $this->config->read('url') . '/node_modules',
            'script_url' => $this->config->read('url') . '/scripts',
            'wiki_version' => Main::WIKI_VERSION,
            'user' => $this->user
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
                        'href' => $this->config->read('url') . '/index.php/management',
                        'visible' => true
                    ],
                    'wiki' => [
                        'label' => 'back_to_wiki',
                        'icon' => 'fa-book',
                        'href' => $this->config->read('url') . '/index.php',
                        'visible' => true
                    ],
                    'logout' => [
                        'label' => 'logout',
                        'icon' => 'fa-sign-out',
                        'href' => $this->config->read('url') . '/index.php/logout',
                        'visible' => !$this->user->isGuest(),
                        'request_confirm' => true
                    ]
                ]
            ],
            'config' => [
                'label' => 'side_config',
                'visible' => true,
                'items' => [
                    'general' => [
                        'label' => 'config_general',
                        'icon' => 'fa-wrench',
                        'href' => $this->config->read('url') . '/index.php/management/settings/general',
                        'visible' => true
                    ],
                    'database' => [
                        'label' => 'config_database',
                        'icon' => 'fa-database',
                        'href' => $this->config->read('url') . '/index.php/management/settings/database',
                        'visible' => true
                    ]
                ]
            ],
            'users' => [
                'label' => 'side_users',
                'visible' => true,
                'items' => [
                    'list' => [
                        'label' => 'display_users',
                        'icon' => 'fa-user',
                        'href' => $this->config->read('url') . '/index.php/management/users',
                        'visible' => true
                    ],
                    'groups' => [
                        'label' => 'manage_groups',
                        'icon' => 'fa-users',
                        'href' => $this->config->read('url') . '/index.php/management/groups',
                        'visible' => true
                    ],
                    'registration' => [
                        'label' => 'registration',
                        'icon' => 'fa-pencil',
                        'href' => $this->config->read('url') . '/index.php/management/registration',
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
                        'href' => $this->config->read('url') . '/index.php/management/security/permissions',
                        'visible' => true
                    ],
                    'bans' => [
                        'label' => 'bans',
                        'icon' => 'fa-ban',
                        'href' => $this->config->read('url') . '/index.php/management/security/banlist',
                        'visible' => true
                    ],
                    'log' => [
                        'label' => 'security_log',
                        'icon' => 'fa-file-text',
                        'href' => $this->config->read('url') . '/index.php/management/security/log',
                        'visible' => true
                    ]
                ]
            ],
            'extend' => [
                'label' => 'side_extend',
                'visible' => true,
                'items' => [
                    'locales' => [
                        'label' => 'locale_management',
                        'icon' => 'fa-language',
                        'href' => $this->config->read('url') . '/index.php/management/settings/general',
                        'visible' => true
                    ],
                    'themes' => [
                        'label' => 'theme_management',
                        'icon' => 'fa-paint-brush',
                        'href' => $this->config->read('url') . '/index.php/management/settings/database',
                        'visible' => true
                    ]
                ]
            ],
        ];

        $visibleTabs = [];

        foreach ($items as $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                $item['label'] = $this->locale->read('management', $item['label']);

                $visibleSubItems = [];
                foreach ($item['items'] as $subItem) {
                    $subItem['label'] = $this->locale->read('management', $subItem['label']);

                    if ($subItem['visible'])
                        $visibleSubItems[] = $subItem;
                }

                // Only display items we can see
                $item['items'] = $visibleSubItems;
                $visibleTabs[] = $item;
            }
        }

        $this->_sidebar = $visibleTabs;
    }

    public function getSidebar() : array {
        return $this->_sidebar;
    }

    public function setTitle(string $value) : void {
        $this->_title = $value;
    }
}