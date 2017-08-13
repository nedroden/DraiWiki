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

class CantProceedException extends Error {

    private $_message, $_locale;

    public function __construct($message) {
        $this->_message = $message;
        $this->_locale = Registry::get('locale', true);
        $this->_locale->loadFile('error');
    }

    public function trigger() : void {
        $message = $this->generateMessage();
        echo Registry::get('gui')->parseAndGet('cant_proceed_exception', $message, false);
    }

    protected function generateMessage() : array {
        return [
            'title' => $this->_locale->read('error', 'cant_proceed_exception'),
            'body' => $this->_message
        ];
    }
}