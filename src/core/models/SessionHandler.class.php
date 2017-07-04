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
use DraiWiki\src\core\controllers\QueryFactory;
use SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface {

    private $_cookieID, $_config;

    public function __construct() {
        $this->_config = Registry::get('config');
        $this->_cookieID = $this->getCookieID();

        session_set_save_handler(
            [$this, 'open'],
            [$this, 'close'],
            [$this, 'read'],
            [$this, 'write'],
            [$this, 'destroy'],
            [$this, 'gc']
        );
        register_shutdown_function('session_write_close');
        session_start();
    }

    /**
     * Servers running Ubuntu or its deratives (this may apply to other Debian-based systems as well) do
     * not automatically run the garbage collector. Thus, we need to call it ourselves.
     * @return void
     */
    public function __destruct() {
        $probability = rand(0, 1000);
        if ($probability == 0)
            $this->gc(ini_get('session.gc_max_lifetime'));
    }

    public function open($savePath, $sessionKey) : bool {
        return true;
    }

    public function close() : bool {
        return true;
    }

    public function read($session_key) : string {
        $query = QueryFactory::produce('select', '
            SELECT `data`
                FROM `{db_prefix}session`
                WHERE session_key = :session_key
                LIMIT 1
        ');

        $query->setParams([
            'session_key' => $session_key
        ]);

        $result = $query->execute();
        foreach ($result as $session)
            return $session['data'];

        return '';
    }

    public function write($session_key, $data) : bool {
        $query = QueryFactory::produce('modify', '
            REPLACE
                INTO `{db_prefix}session`
                VALUES (
                    :session_key,
                    :data,
                    :created_at
                )
        ');

        $query->setParams([
            'session_key' => $session_key,
            'data' => $data,
            'created_at' => time()
        ]);
        $query->execute();

        // If we've made it this far, the query should have been successful.
        return true;
    }

    public function destroy($session_key) : bool {
        $query = QueryFactory::produce('modify', '
            DELETE
                FROM `{db_prefix}session`
                WHERE session_key = :session_key
        ');

        $query->setParams(['session_key' => $session_key]);
        $query->execute();

        return true;
    }

    public function gc($lifetime) : bool {
        $obsolete = time() - $lifetime;
        $query = QueryFactory::produce('modify', '
            DELETE
                FROM `{db_prefix}session`
                WHERE created_at < :obsolete
        ');

        $query->setParams(['obsolete' => $obsolete]);
        $query->execute();

        return true;
    }

    private function getCookieID() {
        return $this->_config->read('cookie_id');
    }
}