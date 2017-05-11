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

class InputValidator {

    private $_value;

    public function __construct($value) {
        $this->_value = $value;
    }

    public function isEmpty() {
        return empty($this->_value);
    }

    public function isTooShort($min) {
        return strlen($this->_value) < $min;
    }

    public function isTooLong($max) {
        return strlen($this->_value) > $max;
    }
}
