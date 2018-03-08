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

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

/**
 * Function equivalent of 'if (!condition) exit'
 * @param bool $result Whether or not the script should stop
 * @return void
 */
function exitIf(bool $result) : void {
    if (!$result)
        exit;
}

/**
 * Takes an array and uses pre tags and the recursive print function to display its elements.
 * @param mixed $array The array that should be printed recursively
 * @return void
 */
function dumpNicely($array) : void {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function applyToAll(array $array, callable $func) : array {
    foreach ($array as &$element)
        $func($element);

    return $array;
}