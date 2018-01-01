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

namespace DraiWiki\external\modules;

class Hook {

    private static $hooks = [
        'copyright' => [],
        'headers' => [],
        'locale' => [],
        'menu' => []
    ];

    public static function callAll(string $section, &$parameter) : void {
        foreach (self::$hooks[$section] as $hook) {
            $hook($parameter);
        }
    }

    public static function add(string $section, callable $func) : void {
        self::$hooks[$section][] = $func;
    }

    public static function addMultiple(array $hooks) : void {
        foreach ($hooks as $key => $value)
            self::$hooks[$key] = array_merge(self::$hooks[$key], [$value]);
    }
}