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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\core\models\Sanitizer;

abstract class AppHeader {

    protected $config, $title;

    protected $hasSidebar = true;

    /**
     * Wether or not main templates should be shown. There are four possible values:
     * 	neither -> Show both the header and the footer
     * 	upper	-> Hide the header, but display the footer
     * 	lower	-> Display the header, but hide the footer
     * 	both	-> Hide both the header and the footer
     *
     * @var string $ignoreTemplates Determines which template parts will be shown
     */
    protected $ignoreTemplates = 'neither';

    protected function loadConfig() : void {
        $this->config = Registry::get('config');
    }

    public function getIgnoreTemplates() : string {
        return $this->ignoreTemplates;
    }

    protected function redirectTo(string $url) : void {
        header('Location: ' . $url);
        die;
    }

    protected function setTitle(string $title) : void {
        $this->title = Sanitizer::ditchUnderscores($title);
    }

    public function execute() : void {
        return;
    }

    public function display() : void {
        return;
    }

    public function getAppInfo() : array {
        $context = [
            'title' => $this->title,
            'permissions' => [],
            'has_sidebar' => $this->hasSidebar
        ];

        return $context;
    }

    public function getSidebarItems() : array {
        return [];
    }
}
