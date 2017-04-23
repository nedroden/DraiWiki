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

if (!defined('DraiWiki') && !defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

/**
 * This class is used for storing objects, so that they can be used at a later point.
 *
 * @since 1.0 Alpha 1
 */
class Registry {

    /**
     * @var array $_objects This array stores the objects that have been added to the registry.
     */
    private static $_objects = [];

    /**
     * This method adds a new object to the registry.
     * @param string $identifier The object identifier/key used to retrieve the object at a later point.
     * @param <object> $object The object that should be added to the array
     * @return object The object you've just created
     */
    public static function set($identifier, $object) {
        self::$_objects[$identifier] = $object;
        return $object;
    }

    /**
     * Retrieve an object from the registry based on the identifier.
     * @param string $identfier The identifier of the object
     * @return object The object the identifier belongs to
     */
    public static function get($identifier) {
        return isset(self::$_objects[$identifier]) ? self::$_objects[$identifier] : null;
    }
}
