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

namespace DraiWiki\src\core\controllers;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class QueryFactory {

    /**
     * This method creates a new query object.
     * @param string $type The type of query
     * @return Object
     */
    public static function produce(string $type) : Query {
        if ($type == 'select')
            return new SelectQuery();
        else if ($type == 'modify')
            return new ModificationQuery();
        else
            die('Unsupported query type.');

        return null;
    }
}
