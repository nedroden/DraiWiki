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

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class PostRequest {

    private $_key;

    private $_value, $_isEmpty;

    public function __construct(string $key) {
        $this->_key = $key;
        $this->setValue();
    }

    private function setValue() {
        if (!empty($_POST[$this->_key])) {
            $this->_value = $_POST[$this->_key];
            $this->_isEmpty = false;
        }
        else {
            $this->_value = '';
            $this->_isEmpty = true;
        }
    }

    public function getIsEmpty() {
        return $this->_isEmpty;
    }

    public function getValue() {
        return $this->_value;
    }
}
