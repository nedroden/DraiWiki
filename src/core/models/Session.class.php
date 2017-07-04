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

class Session {

    private $_config;
    private $_key;
    private $_cookieName, $_cookie;
    private $_length, $_isInfinite, $_data;

    public function __construct(string $key) {
        $this->_key = $key;
        $this->_config = Registry::get('config');
    }

    public function create(int $lengthInMinutes = 60, bool $isInfinite = false, string $cookieName, ?array $data) : void {
        $this->_cookieName = $cookieName;
        $this->_length = $lengthInMinutes;
        $this->_isInfinite = $isInfinite;
        $this->_data = $data ?? [];

        $_SESSION[$this->_key] = $data;
        $this->setCookie();
    }

    private function setCookie() : void {
        $this->_cookie = new Cookie($this->_cookieName, session_id(), $this->_length);
        $this->_cookie->create();
    }

    public function destroy(string $cookieKey) : void {
        if (!empty($_SESSION[$this->_key]))
            unset($_SESSION[$this->_key]);

        setcookie($_COOKIE[$cookieKey], $this->_data, 1, '/', null, $this->_config->read('ssl_enabled'));
    }
}