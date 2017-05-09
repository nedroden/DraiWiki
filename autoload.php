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

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

spl_autoload_register(function($className) {
    $parsedClassName = explode('\\', $className);

    if (count($parsedClassName) > 1) {
        /* We need to get rid of the 'DraiWiki' part, and since classes
           'public' folder do not have the 'public' part included in the
           namespace name, we might as well replace 'DraiWiki' with 'public'.

           Note that templates still need to be included manually, so if
           we're not trying to use a class from the 'src' directory, it
           must be a public directory class. */
        if ($parsedClassName[1] != 'src')
            $parsedClassName[0] = 'public';
        else
            unset($parsedClassName[0]);
    }

    $filename = implode(DIRECTORY_SEPARATOR, $parsedClassName) . '.class.php';

    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $filename))
        require $filename;
    else
        die('Could not load class: ' . $filename);
});
