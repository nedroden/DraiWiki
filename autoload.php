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

spl_autoload_register(function($className) {
    if (count($parsedClassName = explode('\\', $className)) > 1) {
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

    else
        $parsedClassName = implode('\\', $parsedClassName);

    $filename = implode('/', $parsedClassName);

    if (file_exists(__DIR__ . '/' . $filename . '.class.php'))
        require $filename . '.class.php';
    else if (file_exists(__DIR__ . '/' . $filename . '.interface.php'))
        require $filename . '.interface.php';
    else
        die('<strong>[DraiWiki autoload]</strong> Could not load class: ' . $filename);
});
