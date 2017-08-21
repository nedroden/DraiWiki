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

use DraiWiki\src\main\models\{ModelHeader, Table};

class UploadManagement extends ModelHeader {

    public function __construct() {
        $this->loadLocale();
        self::$locale->loadFile('management');
    }

    public function prepareData() : array {
        return [];
    }

    public function getPageDescription() : string {
        return self::$locale->read('management', 'upload_management_description');
    }

    public function getTitle() : string {
        return self::$locale->read('management', 'upload_management');
    }
}