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

namespace DraiWiki\src\main\models;

use DraiWiki\src\core\controllers\Registry;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

class ActionBar extends ModelHeader {

    private $_items, $_localeFile, $_view, $_gui;

    public function __construct(string $localeFile, array $items) {
        $this->_items = $items;

        $this->loadLocale();
        self::$locale->loadFile($localeFile);
        $this->_localeFile = $localeFile;

        $this->_gui = Registry::get('gui');
    }

    public function create() : void {
        foreach ($this->_items as &$item) {
            $item['label'] = _localized($this->_localeFile . '.' . $item['label']);

            if (empty($item['href']))
                $item['href'] = 'javascript:void(0);';
        }

        $this->_view = $this->_gui->parseAndGet('actionbar', ['items' => $this->_items], false);
    }

    public function getBar() : string {
        return $this->_view;
    }
}