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

class CoreException extends Error {

    public function trigger() : void {
        $message = $this->generateMessage();

        if (!empty($gui = Registry::get('gui', true)))
            echo $gui->parseAndGet('core_exception', $message, false);
        else
            echo $message['body'];

        die;
    }

    /**
     * Core exceptions are a little different from regular exceptions. That is why we're overriding the error
     * message generator.
     * @return array
     */
    protected function generateMessage() : array {
        return [
            'title' => $this->hasLocale ? $this->locale->read('error', 'fatal_core_exception') : 'Fatal core exception',
            'body' => $this->hasLocale ? $this->locale->read('error', 'what_is_a_fatal_core_exception') : 'A fatal error occurred that prevented DraiWiki from running.',
            'detailed' => DEBUG_ALWAYS || $this->canViewDetailedInfo() && $this->hasLocale ? $this->locale->read('error', 'yes_you_can') .  '<em>' . $this->detailedInfo . '</em>' : NULL,
            'backtrace' => DEBUG_ALWAYS || $this->canViewDetailedInfo() ? $this->getBacktrace() : []
        ];
    }
}