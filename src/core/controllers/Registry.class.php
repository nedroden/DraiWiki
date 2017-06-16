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

use DraiWiki\src\errors\CoreException;

/**
 * This class is used for storing objects, so that they can be used at a later point.
 *
 * @since 1.0 Alpha 1
 */
class Registry {

    private const ENABLE_REVIEW = false;

    /**
     * @var array $_objects This array stores the objects that have been added to the registry.
     */
    private static $_objects = [];

    /**
     * This method adds a new object to the registry.
     * @param string $identifier The object identifier/key used to retrieve the object at a later point.
     * @param <object> $object The object that should be added to the array
     * @param bool $force Whether or not existing objects should be overridden
     * @return object The object you've just created
     */
    public static function set(string $identifier, $object, bool $force = false) {
        if (!isset(self::$_objects[$identifier]) || $force)
            self::$_objects[$identifier] = $object;
        else
            (new CoreException('Specified object identifier is already in use.'))->trigger();

        return $object;
    }

    /**
     * Retrieve an object from the registry based on the identifier.
     * @param string $identifier The identifier of the object
     * @param bool $ignoreErrors Throw can exception if object is not found? Set to false to throw exceptions
     * @return object The object the identifier belongs to
     */
    public static function get(string $identifier, bool $ignoreErrors = false) {
        if ($ignoreErrors)
            return isset(self::$_objects[$identifier]) ? self::$_objects[$identifier] : null;
        else {
            if (!isset(self::$_objects[$identifier]))
                (new CoreException('Call to unknown registry object: ' . $identifier))->trigger();
            else
                return self::$_objects[$identifier];
        }
    }

    public static function review() : void {
        if (self::ENABLE_REVIEW)
            echo '<pre>', print_r(self::$_objects, true), '</pre>';
    }
}
