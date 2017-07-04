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

class AccessError extends Error {

    private $_locale;

    public function __construct() {
        $this->_locale = Registry::get('locale');
        $this->_locale->loadFile('error');
    }

    public function trigger() : void {
        $message = $this->generateMessage();
        echo Registry::get('gui')->parseAndGet('permission_error', $message, false);
    }

    /**
     * Core exceptions are a little different from regular exceptions. That is why we're overriding the error
     * message generator.
     * @return array
     */
    protected function generateMessage() : array {
        return [
            'title' => $this->_locale->read('error', 'access_denied'),
            'body' => $this->_locale->read('error', 'access_denied_why')
        ];
    }
}