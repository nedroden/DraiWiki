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

namespace DraiWiki\src\errors;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

class AccessError extends Error {

    public function __construct() {
        $this->locale = Registry::get('locale', true);
    }

    public function trigger() : void {
        $message = $this->generateMessage();
        echo Registry::get('gui')->parseAndGet('permission_error', $message, false);
    }

    protected function generateMessage() : array {
        return [
            'title' => _localized('error.access_denied'),
            'body' => _localized('error.access_denied_why')
        ];
    }
}