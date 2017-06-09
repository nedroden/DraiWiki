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

namespace DraiWiki\src\errors;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\controllers\GUI;

class Error {

    private $_hasGUI, $_GUI;

    protected $detailedInfo;
    protected $locale;

    public function __construct(string $detailedInfo) {
        ob_clean();

        $this->_hasGUI = false;
        $this->detailedInfo = $detailedInfo;

        $this->locale = Registry::get('locale', true);
        $this->hasLocale = $this->locale != NULL;

        if ($this->hasLocale)
            $this->locale->loadFile('error');
    }

    public function setGUI(GUI $gui) : void {
        $this->_hasGUI = true;
        $this->_GUI = $gui;
    }

    protected function canViewDetailedInfo() : bool {
        return true;
    }

    protected function generateMessage() : array {
        return [
            'title' => $this->hasLocale ? $this->_locale->read('error', 'something_went_wrong') : 'Something went wrong',
            'body' => $this->hasLocale ? $this->_locale->read('error', 'generic_error_message') : 'An error occurred. Please contact the administrator.',
            'detailed' => $this->canViewDetailedInfo() ? $this->detailedInfo : NULL
        ];
    }
}