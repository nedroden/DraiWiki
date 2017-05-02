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

namespace DraiWiki\admin\controllers;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

/**
 * This class loads the locale.
 *
 * @since		1.0 Alpha 1
 */
class Locale {

    /**
     * @var string $_native The name of the language in the language itself, e.g. castellano, Русский, ...
     */
    private $_native;

    /**
     * @var string $_code The language's code, e.g. es_ES, ru_RU, en_US...
     */
    private $_code;

    /**
     * @var Config $_config The object that contains all admin panel-related settings
     */
    private $_config;

    public function __construct() {
        $this->_config = Registry::get('conf_admin');
    }

    public function loadFile($filename) {
        if (file_exists($fileLocation = $this->_config->read('path', 'BASE_ADMIN_PATH') . '/lang/' . $this->_code . '/' . $filename . '.language.php'))
            require_once $fileLocation;
        else
            die('Could not load language file "' . $filename . '"');
    }

    public function read($category, $key, $return = true) {
		if ($return && !empty($this->_strings[$category][$key]))
			return $this->_strings[$category][$key];
		else if (!$return && !empty($this->_strings[$category][$key]))
			echo $this->_strings[$category][$key];
		else if ($return)
			return '<span class="stringNotFound">String not found: ' . $category . '.' . $key . '</span>';
		else
			echo '<span class="stringNotFound">String not found ', $category, '.', $key , '</span>';

		return null;
	}
}
