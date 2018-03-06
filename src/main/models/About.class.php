<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018 DraiWiki
 * @license     Apache 2.0
 *
 * Note: this file depends on the FastRoute routing library.
 */

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class About extends ModelHeader {

    private $_libraries;
    private $_teamMembers;

    public function __construct() {
        $this->loadLocale();
        $this->getLibraries();
        $this->getTeamMembers();
    }

    public function prepareData() : array {
        return [
            'libraries' => $this->_libraries,
            'team_members' => $this->_teamMembers
        ];
    }

    private function getTeamMembers() : void {
        $this->_teamMembers = [
            'robert' => [
                'name' => 'Robert Monden',
                'website' => 'https://robertmonden.com',
                'email' => 'dev@robertmonden.com'
            ]
        ];
    }

    private function getLibraries() : void {
        $this->_libraries = [
            ['name' => 'Chart.js', 'href' => 'http://chartjs.org'],
            ['name' => 'CodeMirror', 'href' => 'https://codemirror.net/'],
            ['name' => 'CodeMirror spell checker', 'href' => 'https://codemirror.net/'],
            ['name' => 'Color convert', 'href' => 'https://github.com/Qix-/color-convert'],
            ['name' => 'Color name', 'href' => 'https://github.com/colorjs/color-name'],
            ['name' => 'Cookie consent', 'href' => 'https://cookieconsent.insites.com'],
            ['name' => 'Font Awesome', 'href' => 'http://fontawesome.io/'],
            ['name' => 'jQuery', 'href' => 'https://jquery.com'],
            ['name' => 'Marked', 'href' => 'https://github.com/chjj/marked'],
            ['name' => 'Moment', 'href' => 'http://momentjs.com'],
            ['name' => 'Parsedown', 'href' => 'http://parsedown.org'],
            ['name' => 'PHP Debug Bar', 'href' => 'http://phpdebugbar.com/'],
            ['name' => 'Select2', 'href' => 'https://select2.github.io'],
            ['name' => 'SimpleMail', 'href' => 'https://github.com/eoghanobrien/php-simple-mail'],
            ['name' => 'SimpleMDE', 'href' => 'https://simplemde.com'],
            ['name' => 'Sprintf.js', 'href' => 'https://github.com/alexei/sprintf.js'],
            ['name' => 'Twig', 'href' => 'https://twig.symfony.com/'],
            ['name' => 'TypeWatch', 'href' => 'https://github.com/dennyferra/TypeWatch'],
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

    public function getTitle() : string {
        return _localized('main.about_this_software');
    }
}