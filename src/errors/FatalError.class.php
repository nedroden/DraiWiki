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

namespace DraiWiki\src\errors;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

class FatalError extends Error {

    public function trigger() : void {
        $message = $this->generateMessage();

        if (!empty($gui = Registry::get('gui', true)))
            echo $gui->parseAndGet('fatal_error', $message, false);
        else
            echo $message;

        die;
    }
}