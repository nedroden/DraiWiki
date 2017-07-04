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

if (!defined('DraiWiki') && !defined('DWA')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

class Cookie {

    private $_key, $_data, $_length;
    private $_config;

    public function __construct(string $key, string $data, ?int $length) {
        $this->_key = $key;
        $this->_data = $data;
        $this->_length = time() + ($length ?? 10 * 365 * 24 * 60 * 60);
        $this->_config = Registry::get('config');
    }

    public function create() : void {
        setcookie($this->_key, $this->_data, $this->_length, '/', null, $this->_config->read('ssl_enabled'));
    }

    public function destroy() : void {
        setcookie($this->_key, $this->_data, 1, '/', null, $this->_config->read('ssl_enabled'));
    }
}