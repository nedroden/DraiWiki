<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\core\models;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Sanitizer {

    public static function ditchUnderscores(string $value) : string {
        return str_replace('_', ' ', $value);
    }

    public static function addUnderscores(string $value) : string {
        return str_replace(' ', '_', $value);
    }

    public static function escapehtml(string $value, string $charset = 'UTF-8') : string {
    	return htmlspecialchars($value, ENT_NOQUOTES, $charset);
    }

    public static function nullToString(string $replaceWith, ?string $source) : string {
        return $source == NULL ? $replaceWith : $source;
    }
}
