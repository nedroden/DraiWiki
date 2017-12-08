<?php
namespace DraiWiki\external\modules\dummy;

use DraiWiki\external\modules\{Hook, Module};
use DraiWiki\src\main\models\AppHeader;

class Dummy extends AppHeader implements Module {

    private $_model;

    public function __construct() {
        //$this->_model = new Model();
    }

    public function callHooks() : void {
        Hook::addMultiple([
            'copyright' => [$this, 'getCopyright'],
            'locale' => [$this, 'getLocaleFiles'],
            'menu' => [$this, 'myMenuFunc']
        ]);
    }

    public function myMenuFunc(array &$menuItems) : void {
        $menuItems['resources'] = [
            'label' => 'resources',
            'href' => 'https://duckduckgo.com',
            'visible' => true
        ];
    }

    public function getLocaleFiles(array &$files) : void {
        $files[] = 'Dummy.dummy';
    }

    public function getCopyright(string &$copyright) : void {
        $copyright .= '<br />This is a really cool module';
    }
}