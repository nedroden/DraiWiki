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

class Sanitizer {

    public static function ditchUnderscores(string $value) {
        return str_replace('_', ' ', $value);
    }

    public static function addUnderscores(string $value) {
        return str_replace(' ', '_', $value);
    }

    public static function escapehtml(string $value, string $charset = 'UTF-8') {
    	return htmlspecialchars($value, ENT_NOQUOTES, $charset);
    }
}
