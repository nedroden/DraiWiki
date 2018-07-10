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

namespace DraiWiki\src\admin\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\main\models\{ModelHeader, Table};

class GroupManagement extends ModelHeader {

    public function __construct() {

    }

    public function prepareData() : array {
        return [];
    }

    public function getPageDescription() : string {
        return _localized('management.group_management_description');
    }

    public function getTitle() : string {
        return _localized('management.group_management');
    }
}