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

namespace DraiWiki\admin\models;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

/**
 * This class contains several template-related methods. All templates should
 * extend this class.
 *
 * @since		1.0 Alpha 1
 */
abstract class Template {

    protected $locale, $user, $data, $stylesheets = [];

    protected static $config;

    private function loadConfig() {
        self::$config = Registry::get('conf_admin');
    }

    protected function getStylesheet($name) {
        if (empty(self::$config))
            $this->loadConfig();

        return $this->config->read('path', 'BASE_ADMIN_URL') . '/stylesheet.php?id=' . lcfirst($name);
    }

    protected function loadLocale() {
        $this->locale = Registry::get('locale');
    }

    protected function getCopyright() {
        return 'Powered by <a href="http://draiwiki.robertmonden.com" target="_blank">DraiWiki</a> ' . DraiWikiVersion . ' |
            &copy; ' . date("Y") . ' <a href="http://robertmonden.com" taret="_blank">Robert Monden</a>';
    }

    protected function getScriptLocation($name) {
        if (empty(self::$config))
            $this->loadConfig();

        return $this->config->read('path', 'BASE_ADMIN_PATH') . '/javascript/' . $name . '.js';
    }

    public function setData($data = []) {
        if (!is_array($data))
            return false;
        else if (empty($this->data))
            $this->data = $data;
        else
            $this->data = array_merge($this->data, $data);
    }

    public function pushStylesheets($stylesheets) {
        $this->stylesheets = $stylesheets;
    }
}
