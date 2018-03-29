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

namespace DraiWiki\src\main\controllers;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\external\modules\Hook;
use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\{DebugBarWrapper, Locale as LocaleModel};
use Exception;
use Twig_Environment;
use Twig_Function;
use Twig_Loader_Filesystem;

class GUI {

    private $_config;
    private $_templatePath, $_skinUrl, $_imageUrl;
    private $_copyright;
    private $_user;
    private $_teamMembers, $_libraries;

    private $_loader;
    private $_twig;
    private $_data;

    private const DEFAULT_THEME = 'Hurricane';

    public static $gui_loaded = false;

    public function __construct() {
        $this->_config = Registry::get('config');
        $this->_locale = Registry::get('locale');
        $this->_user = Registry::get('user');

        $this->_data = [];

        $this->setThemeInfo();
        $this->setCopyright();
        $this->generateMenu();

        $this->_loader = new Twig_Loader_Filesystem($this->_templatePath);
        $this->_twig = new Twig_Environment($this->_loader, [
            'cache' => DEBUG_ALWAYS ? false : $this->_config->read('path') . '/cache',
            'debug' => DEBUG_ALWAYS,
            'autoescape' => false
        ]);

        $this->_twig->addFunction(new Twig_Function('_localized', function(string $identifier, string ...$params) {
            return _localized($identifier, ...$params);
        }));

        $moduleHeaders = '';

        Hook::callAll('copyright', $this->_copyright);
        Hook::callAll('headers', $moduleHeaders);

        $this->_data += [
            'base_url' => $this->_config->read('url'),
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
            'display_cookie_warning' => $this->_config->read('display_cookie_warning') == 1,
            'locale_continents' => $this->getLocalesByContinent(),
            'slogan' => $this->_config->read('slogan'),
            'module_headers' => $moduleHeaders
        ];
    }

    public function showHeader() : void {
        echo $this->_twig->load('header.tpl')->render(array_merge($this->_data, $this->createDebugBar(false, 'head')));
    }

    public function showFooter() : void {
        echo $this->_twig->load('footer.tpl')->render(array_merge($this->_data, $this->createDebugBar(false, 'body')));
    }

    public function setData(array $data) : void {
        $this->_data = array_merge($this->_data, $data);
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

        return $this->_twig->load($tplName . '.tpl')->render($this->_data + $data);
    }

    private function setThemeInfo() : void {
        if (file_exists($this->_config->read('path') . '/public/views/templates/' . $this->_config->read('templates') . '/header.tpl'))
            $this->_templatePath = $this->_config->read('path') . '/public/views/templates/' . $this->_config->read('templates');
        else if ($this->_config->read('templates') != self::DEFAULT_THEME)
            $this->_templatePath = $this->_config->read('path') . '/public/views/templates/' . self::DEFAULT_THEME;
        else
            die('Templates not found.');

        $this->_skinUrl = $this->_config->read('url') . '/index.php/stylesheet/';

        if (file_exists($this->_config->read('path') . '/public/views/images/' . $this->_config->read('images') . '/index.php'))
            $this->_imageUrl = $this->_config->read('url') . '/public/views/images/' . $this->_config->read('images');
        else if ($this->_config->read('images') != self::DEFAULT_THEME)
            $this->_imageUrl = $this->_config->read('url') . '/public/views/images/' . self::DEFAULT_THEME;
        else
            die('Skins not found.');

        self::$gui_loaded = true;
    }

    private function setCopyright() : void {
        $this->_copyright = 'Powered by <a href="' . $this->_config->read('url') . '/index.php/about" target="_blank">DraiWiki</a> ' . Main::WIKI_VERSION;
    }

    private function generateMenu() : void {
        $menu = [
            'home' => [
                'label' => 'home',
                'href' => $this->_config->read('url') . '/index.php',
                'visible' => true
            ],
            'activity' => [
                'label' => 'activity',
                'href' => $this->_config->read('url') . '/index.php/activity',
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

        Hook::callAll('menu', $menu);

        $visible_tabs = [];

        foreach ($menu as $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                $item['label'] = _localized('main.' . $item['label']);
                $visible_tabs[] = $item;
            }
        }

        $this->_data['menu'] = $visible_tabs;
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
                    'activity' => [
                        'label' => 'activity',
                        'href' => $this->_config->read('url') . '/index.php/activity',
                        'visible' => true,
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
                    'account_settings' => [
                        'label' => 'account_settings',
                        'href' => $this->_config->read('url') . '/index.php/account/settings',
                        'visible' => !$this->_user->isGuest()
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
            ],
            'tools' => [
                'label' => 'side_tools',
                'visible' => true,
                'items' => [
                    'find_article' => [
                        'label' => 'find_article',
                        'href' => $this->_config->read('url') . '/index.php/find',
                        'visible' => $this->_user->hasPermission('find_article')
                    ],
                    'upload_images' => [
                        'label' => 'upload_images',
                        'href' => $this->_config->read('url') . '/index.php/imageupload',
                        'visible' => $this->_user->hasPermission('upload_images')
                    ],
                    'resources' => [
                        'label' => 'resources',
                        'href' => $this->_config->read('url') . '/index.php/resources',
                        'visible' => true
                    ]
                ]
            ]
        ];

        $visibleTabs = [];
        $items = array_merge($items, $additionalItems);

        foreach ($items as $item) {
            if ($item['visible']) {
                // Replace the label placeholders with localized labels
                if (empty($item['hardcoded']) || !$item['hardcoded'])
                    $item['label'] = _localized('main.' . $item['label']);

                $visibleSubItems = [];
                foreach ($item['items'] as $subItem) {
                    if (empty($subItem['hardcoded']) || !$subItem['hardcoded'])
                        $subItem['label'] = _localized('main.' . $subItem['label']);

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

    private function getLocalesByContinent() : array {
        $query = QueryFactory::produce('select', '
            SELECT id
                FROM {db_prefix}locale
                WHERE id != :locale_id
        ');

        $query->setParams([
            'locale_id' => $this->_locale->getCurrentLocaleInfo()->getID()
        ]);

        $locales = [];
        foreach ($query->execute() as $foundLocale) {
            try {
                $locale = new LocaleModel($foundLocale['id']);
            }
            catch (Exception $e) {
                continue;
            }

            $localeContinent = $locale->getContinent();

            if (empty($locales[$localeContinent])) {
                $locales[$localeContinent] = [
                    'label' => _localized('main.continent_' . $localeContinent, true, true) ?? _localized('main.continent_other'),
                    'locales' => []
                ];
            }

            $locales[$localeContinent]['locales'][] = [
                'native' => $locale->getNative() . ($locale->isDefault() ? ' - ' . _localized('main.default') : ''),
                'code' => $locale->getCode()
            ];
        }

        $currentLocaleContinent = $this->_locale->getCurrentLocaleInfo()->getContinent();
        if (empty($locales[$currentLocaleContinent])) {
            $locales[$currentLocaleContinent] = [
                'label' => _localized('main.continent_' . $currentLocaleContinent, true, true) ?? _localized('main.continent_other'),
                'locales' => []
            ];
        }

        $locales[$currentLocaleContinent]['locales'][] = [
            // Default locale does not necessarily have to be the current locale, so we should still check
            'native' => $this->_locale->getCurrentLocaleInfo()->getNative() . ($this->_locale->getCurrentLocaleInfo()->isDefault() ? ' - ' . _localized('main.default') : ''),
            'code' => $this->_locale->getCurrentLocaleInfo()->getCode(),
            'selected' => true
        ];

        uasort($locales, function(array $a, array $b) {
            return $a['label'] <=> $b['label'];
        });

        foreach ($locales as $locale) {
            uasort($locale['locales'], function(array $a, array $b) {
                return $a['native'] <=> $b['native'];
            });
        }

        return $locales;
    }

    private function createDebugBar(bool $return = false, string $part = 'both') : ?array {
        $canView = $this->_user->isRoot();
        $renderer = DebugBarWrapper::getRenderer();

        $data = [];

        if ($part == 'both' || $part == 'head')
            $data['debug_head'] = $canView ? $renderer->renderHead() : null;

        if ($part == 'both' || $part == 'body')
            $data['debug_body'] = $canView ? $renderer->render() : null;

        return $data ?? null;
    }

    public static function getThemeDirectories(string $dirname, string $findFile) : array {
        $config = Registry::get('config');
        $files = @scandir($config->read('path') . '/public/views/' . $dirname);

        if (!$files)
            return [];

        $directories = [];
        foreach ($files as $file) {
            if ($file == '.'
                || $file == '..'
                || !is_dir($config->read('path') . '/public/views/' . $dirname . '/' . $file)
                || !file_exists($config->read('path') . '/public/views/' . $dirname . '/' . $file . '/' . $findFile))
                continue;

            $directories[] = $file;
        }

        return $directories;
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
}
