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

    protected static $user, $config;

    protected $title, $requiredPermission;
    protected $hasSidebar = true;
    protected $cantProceedException;
    protected $ajax, $parsedAJAXRequest;

    private $_ajaxRequest;

    /**
     * Whether or not main templates should be shown. There are four possible values:
     * 	neither -> Show both the header and the footer
     * 	upper	-> Hide the header, but display the footer
     * 	lower	-> Display the header, but hide the footer
     * 	both	-> Hide both the header and the footer
     *
     * @var string $ignoreTemplates Determines which template parts will be shown
     */
    protected $ignoreTemplates = 'neither';

    protected function loadConfig() : void {
        self::$config = Registry::get('config');
    }

    protected function loadUser() : void {
        self::$user = Registry::get('user');
    }

    public function getIgnoreTemplates() : string {
        return $this->ignoreTemplates;
    }

    protected function redirectTo(string $url) : void {
        header('Location: ' . $url);
        die;
    }

    public function setTitle(string $title) : void {
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
            'has_sidebar' => $this->hasSidebar,
            'ajax' => $this->ajax
        ];

        return $context;
    }

    public function getSidebarItems() : array {
        return [];
    }

    public function getCantProceedException() : ?string {
        return $this->cantProceedException;
    }

    public function canAccess() : bool {
        if (empty(self::$user))
            $this->loadUser();

        return empty($this->requiredPermission) ? true : self::$user->hasPermission($this->requiredPermission);
    }

    protected function checkForAjax() : void {
        $route = Registry::get('route');

        if (!empty($route->getParams()['ajax_request'])) {
            $this->ajax = true;
            $this->ignoreTemplates = 'both';
            $this->hasSidebar = false;
            $this->_ajaxRequest = $route->getParams()['ajax_request'];
        }

        else
            $this->ajax = false;
    }

    public function parseAjaxRequest() : void {
        $requests = explode(';', $this->_ajaxRequest);
        $parameters = [];

        foreach ($requests as $parts) {
            $parsed = explode('_', $parts);

            if (count($parsed) == 2)
                $parameters[$parsed[0]] = $parsed[1];
            else if (count($parsed) == 1)
                $parameters[$parsed[0]] = 1;
        }

        $this->parsedAJAXRequest = $parameters;
    }

    public function printJSON() : void {
        return;
    }

    public function getAdditionalHeaders() : ?string {
        return null;
    }
}
