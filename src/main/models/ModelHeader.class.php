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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

abstract class ModelHeader {

    protected static $locale, $config, $user;

    protected function loadLocale() : void {
        self::$locale = Registry::get('locale');
    }

    protected function loadConfig() : void {
        self::$config = Registry::get('config');
    }

    public function loadUser() : void {
        self::$user = Registry::get('user');
    }

    public function prepareData() : array {
        return [];
    }

    public function generateJSON() : string {
        return '';
    }
}
