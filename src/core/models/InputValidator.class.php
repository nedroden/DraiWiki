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

    public function __construct(string $value) {
        $this->_value = $value;
    }

    public function isEmpty() : bool {
        return empty($this->_value);
    }

    public function isTooShort(int $min) {
        return strlen($this->_value) < $min;
    }

    public function isTooLong(int $max) {
        return strlen($this->_value) > $max;
    }
}
