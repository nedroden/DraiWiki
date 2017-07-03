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

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\{Registry, QueryFactory};

class SettingsImporter {

    public static function execute() : void {
        $config = Registry::get('config');

        $query = QueryFactory::produce('select', '
            SELECT `key`, `value`
                FROM {db_prefix}setting
        ');

        $result = $query->execute();
        $updatedSettings = [];

        foreach ($result as $setting)
            $updatedSettings[$setting['key']] = $setting['value'];

        $config->import($updatedSettings);
    }
}