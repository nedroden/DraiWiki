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

/**
 * Function equivalent of 'if (!condition) exit'
 * @param bool $result Whether or not the script should stop
 */
function exitIf(bool $result) : void {
    if (!$result)
        exit;
}

/**
 * Takes an array and uses pre tags and the recursive print function to display its elements.
 * @param mixed $array The array that should be printed recursively
 */
function dumpNicely($array) : void {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}