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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\DebugBarWrapper;
use Dwoo\{Core, Data};

class GUI {

    private $_config;
    private $_engine;
    private $_templatePath, $_skinUrl, $_imageUrl;
    private $_data;
    private $_copyright;
    private $_user;
    private $_teamMembers, $_libraries;

    private const DEFAULT_THEME = 'Hurricane';

    public static $gui_loaded = false;

    public function __construct() {
        $this->_config = Registry::get('config');
        $this->_locale = Registry::get('locale');
        $this->_user = Registry::get('user');
        $this->_engine = new Core();

        $this->_data = new Data();

        $this->setThemeInfo();
        $this->setCopyright();
        $this->generateMenu();
        $this->_engine->setTemplateDir($this->_templatePath . '/');

        // Unfortunately we can't use sprintf in templates, so we have to do this manually
        $this->_locale->replace('main', 'hello', $this->_user->getUsername());

        $this->setLibraries();
        $this->setTeamMembers();

        $this->setData([
            'skin_url' => $this->_skinUrl,
            'image_url' => $this->_imageUrl,
            'locale' => $this->_locale,
            'copyright' => $this->_copyright,
            'node_url' => $this->_config->read('url') . '/node_modules',
            'script_url' => $this->_config->read('url') . '/scripts',
            'wiki_version' => Main::WIKI_VERSION,
            'teams' => $this->_teamMembers,
            'packages' => $this->_libraries,
            'user' => $this->_user,
            'display_cookie_warning' => $this->_config->read('display_cookie_warning') == 1
        ]);
    }

    public function showHeader() : void {
        $this->createDebugBar(false, 'head');

        echo $this->_engine->get('header.tpl', $this->_data);
    }

    public function showFooter() : void {
        $this->createDebugBar(false, 'body');

        echo $this->_engine->get('footer.tpl', $this->_data);
    }

    public function setData(array $data) : void {
        foreach ($data as $key => $value)
            $this->_data->assign($key, $value);
    }

    public function parseAndGet(string $tplName, array $data, bool $canThrowException = true) : string {
        if (!file_exists($this->_templatePath . '/' . $tplName . '.tpl')) {
            if ($canThrowException)
                die('Exception thrown.');
            else
                die('Template not found.');
        }

        if ($tplName == 'admin_header' || $tplName == 'admin_footer')
            $data = array_merge($data, $this->createDebugBar(true));

        $data = array_merge([
            'skin_url' => $this->_skinUrl,
            'image_url' => $this->_imageUrl,
            'node_url' => $this->_config->read('url') . '/node_modules',
            'locale' => $this->_locale,
            'copyright' => $this->_copyright,
            'script_url' => $this->_config->read('url') . '/scripts',
            'user' => $this->_user
        ], $data);

        $dataObject = new Data();
        foreach ($data as $key => $value)
            $dataObject->assign($key, $value);

        return $this->_engine->get($tplName . '.tpl', $dataObject);
    }

    private function setThemeInfo() : void {
        if (file_exists($this->_config->read('path') . '/public/views/templates/' . $this->_config->read('templates') . '/header.tpl'))
            $this->_templatePath = $this->_config->read('path') . '/public/views/templates/' . $this->_config->read('templates');
        else if ($this->_config->read('templates') != self::DEFAULT_THEME)
            $this->_templatePath = $this->_config->read('path') . '/public/views/templates/' . self::DEFAULT_THEME;
        else
            die('Templates not found.');

        $this->_skinUrl = $this->_config->read('url') . '/stylesheet.php?id=';

        if (file_exists($this->_config->read('path') . '/public/views/images/' . $this->_config->read('images') . '/index.php'))
            $this->_imageUrl = $this->_config->read('url') . '/public/views/images/' . $this->_config->read('images');
        else if ($this->_config->read('images') != self::DEFAULT_THEME)
            $this->_imageUrl = $this->_config->read('url') . '/public/views/images/' . self::DEFAULT_THEME;
        else
            die('Skins not found.');

        self::$gui_loaded = true;
    }

    private function setCopyright() : void {
        $this->_copyright = 'Powered by <a href="https://draiwiki.robertmonden.com" target="_blank" id="dw-about-link">DraiWiki</a> ' . Main::WIKI_VERSION;
    }

    private function setTeamMembers() : void {
        $this->_teamMembers = [
            'president' => [
                'label' => $this->_locale->read('main', 'team_president'),
                'members' => [
                    'robert' => [
                        'name' => 'Robert Monden',
                        'website' => 'https://robertmonden.com',
                        'email' => 'dev@robertmonden.com'
                    ]
                ]
            ]
        ];
    }

    private function setLibraries() : void {
        $this->_libraries = [
            ['name' => 'Chart.js', 'href' => 'http://chartjs.org'],
            ['name' => 'CodeMirror', 'href' => 'https://codemirror.net/'],
            ['name' => 'CodeMirror spell checker', 'href' => 'https://codemirror.net/'],
            ['name' => 'Color convert', 'href' => 'https://github.com/Qix-/color-convert'],
            ['name' => 'Color name', 'href' => 'https://github.com/colorjs/color-name'],
            ['name' => 'Cookie consent', 'href' => 'https://cookieconsent.insites.com'],
            ['name' => 'Dwoo', 'href' => 'http://dwoo.org'],
            ['name' => 'Font Awesome', 'href' => 'http://fontawesome.io/'],
            ['name' => 'jQuery', 'href' => 'https://jquery.com'],
            ['name' => 'Marked', 'href' => 'https://github.com/chjj/marked'],
            ['name' => 'Moment', 'href' => 'http://momentjs.com'],
            ['name' => 'Parsedown', 'href' => 'http://parsedown.org'],
            ['name' => 'PHP Debug Bar', 'href' => 'http://phpdebugbar.com/'],
            ['name' => 'SimpleMail', 'href' => 'https://github.com/eoghanobrien/php-simple-mail'],
            ['name' => 'SimpleMDE', 'href' => 'https://simplemde.com'],
            ['name' => 'Sprintf.js', 'href' => 'https://github.com/alexei/sprintf.js'],
            ['name' => 'Typo JS', 'href' => 'https://github.com/cfinke/Typo.js/'],
            ['name' => 'Zebra Dialog', 'href' => 'https://github.com/stefangabos/Zebra_Dialog']
        ];

        /**
         * Missing dependencies of the debug bar:
         * - Installing symfony/polyfill-mbstring (v1.4.0): Loading from cache
         * - Installing symfony/var-dumper (v3.3.5): Downloading (100%)
         * - Installing psr/log (1.0.2): Loading from cache
         */
    }

    private function generateMenu() : void {
        $menu = [
            'home' => [
                'label' => 'home',
                'href' => $this->_config->read('url') . '/index.php',
                'visible' => true
            ],
            'manage' => [
                'label' => 'manage',
                'href' => $this->_config->read('url') . '/index.php/management',
                'visible' => $this->_user->hasPermission('manage_site')
            ],
            'login' => [
                'label' => 'login',
                'href' => $this->_config->read('url') . '/index.php/login',
                'visible' => $this->_user->isGuest()
            ],
            'register' => [
                'label' => 'register',
                'href' => $this->_config->read('url') . '/index.php/register',
                'visible' => $this->_user->isGuest()
            ],
            'logout' => [
                'label' => 'logout',
                'href' => $this->_config->read('url') . '/index.php/logout',
                'visible' => !$this->_user->isGuest()
            ]
        ];

        $visible_tabs = [];

        foreach ($menu as $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                $item['label'] = $this->_locale->read('main', $item['label']);
                $visible_tabs[] = $item;
            }
        }

        $this->setData(['menu' => $visible_tabs]);
    }

    public function displaySidebar(array $additionalItems) : void {
        $items = [
            'main' => [
                'label' => 'side_main',
                'visible' => true,
                'items' => [
                    'home' => [
                        'label' => 'home',
                        'href' => $this->_config->read('url') . '/index.php',
                        'visible' => true
                    ],
                    'random' => [
                        'label' => 'random',
                        'href' => $this->_config->read('url') . '/index.php/random',
                        'visible' => true
                    ],
                    'manage' => [
                        'label' => 'manage',
                        'href' => $this->_config->read('url') . '/index.php/management',
                        'visible' => $this->_user->hasPermission('manage_site')
                    ],
                    'login' => [
                        'label' => 'login',
                        'href' => $this->_config->read('url') . '/index.php/login',
                        'visible' => $this->_user->isGuest()
                    ],
                    'register' => [
                        'label' => 'register',
                        'href' => $this->_config->read('url') . '/index.php/register',
                        'visible' => $this->_user->isGuest()
                    ],
                    'logout' => [
                        'label' => 'logout',
                        'href' => $this->_config->read('url') . '/index.php/logout',
                        'visible' => !$this->_user->isGuest()
                    ]
                ]
            ]
        ];

        $visibleTabs = [];
        $items = array_merge($items, $additionalItems);

        foreach ($items as $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                $item['label'] = $this->_locale->read('main', $item['label']);

                $visibleSubItems = [];
                foreach ($item['items'] as $subItem) {
                    $subItem['label'] = $this->_locale->read('main', $subItem['label']);

                    if ($subItem['visible'])
                        $visibleSubItems[] = $subItem;
                }

                // Only display items we can see
                $item['items'] = $visibleSubItems;
                $visibleTabs[] = $item;
            }
        }

        echo $this->parseAndGet('sidebar', ['items' => $visibleTabs]);
    }

    private function createDebugBar(bool $return = false, string $part = 'both') : ?array {
        $canView = $this->_user->isRoot();
        $renderer = DebugBarWrapper::getRenderer();

        $data = [];

        if ($part == 'both' || $part == 'head')
            $data['debug_head'] = $canView ? $renderer->renderHead() : null;

        if ($part == 'both' || $part == 'body')
            $data['debug_body'] = $canView ? $renderer->render() : null;

        if (!$return)
            $this->setData($data);
        else
            return $data;

        return null;
    }

    public function getSkinUrl() : string {
        return $this->_skinUrl;
    }

    public function getImageUrl() : string {
        return $this->_imageUrl;
    }

    public function getCopyright() : string {
        return $this->_copyright;
    }

    public function getTeamMembers() : array {
        return $this->_teamMembers;
    }

    public function getLibraries() : array {
        return $this->_libraries;
    }
}
