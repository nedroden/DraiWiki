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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DebugBar;
use DraiWiki\src\core\controllers\Registry;

class DebugBarWrapper {

    private static $_bar;

    public static function create() : void {
        self::$_bar = new DebugBar\StandardDebugBar();
    }

    public static function report(string $message) : void {
        self::$_bar['messages']->addMessage($message);
    }

    public static function getRenderer() : DebugBar\JavascriptRenderer {
        return self::$_bar->getJavascriptRenderer()->setBaseUrl(Registry::get('config')->read('url') . '/vendor/maximebf/debugbar/src/DebugBar/Resources');
    }
}