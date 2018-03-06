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

namespace DraiWiki\src\core\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\errors\CoreException;

class QueryFactory {

    /**
     * This method creates a new query object.
     * @param string $type The type of query
     * @return Object
     */
    public static function produce(string $type, string $query) : Query {
        switch($type) {
            case 'select':
                return new SelectQuery($query);
                break;
            case 'modify':
                return new ModificationQuery($query);
                break;
            default:
                (new CoreException('Query type "' . $type . "' not supported'", false))->trigger();
        }
    }
}
