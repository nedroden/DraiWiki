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

abstract class ModelHeader {

    protected $locale, $config, $user;

    protected function loadLocale() : void {
        $this->locale = Registry::get('locale');
    }

    protected function loadConfig() : void {
        $this->config = Registry::get('config');
    }

    public function loadUser() : void {
        $this->user = Registry::get('user');
    }

    public function prepareData() : array {
        return [];
    }

    public function generateJSON() : string {
        return '';
    }
}
