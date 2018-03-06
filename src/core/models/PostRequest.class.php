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

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

class PostRequest {

    private $_key;
    private $_value, $_isEmpty, $_isSet;
    private $_config;

    public function __construct(string $key) {
        $this->_key = $key;
        $this->setValue();

        $this->_config = Registry::get('config');
    }

    private function setValue() : void {
        if (!empty($_POST[$this->_key])) {
            $this->_value = $_POST[$this->_key];
            $this->_isEmpty = false;
            $this->_isSet = true;
        }
        else {
            $this->_value = '';
            $this->_isEmpty = true;
            $this->_isSet = isset($_POST[$this->_key]);
        }
    }

    public function getIsEmpty() : bool {
        return $this->_isEmpty;
    }

    public function getIsSet() : bool {
        return $this->_isSet;
    }

    public function getValue() : string {
        return $this->_value;
    }

    public function escapeHTML() : void {
        $this->_value = Sanitizer::escapeHTML($this->_value);
    }

    public function trim() : void {
        $this->_value = trim($this->_value);
    }

    public function getHash() : string {
        $salt = $this->_config->read('password_salt');
        return password_hash($this->_value . $salt, PASSWORD_BCRYPT, ['salt' => $salt]);
    }
}
