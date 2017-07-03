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

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\{InputValidator, PostRequest, Sanitizer};
use DraiWiki\src\main\models\ModelHeader;

class Login extends ModelHeader {

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();
        $this->locale->loadFile('auth');
    }

    public function prepareData() : array {
        return [
            'max_email_length' => $this->config->read('max_email_length'),
            'max_password_length' => $this->config->read('max_password_length')
        ];
    }

    public function getTitle() : string {
        return $this->locale->read('auth', 'logging_in');
    }
}