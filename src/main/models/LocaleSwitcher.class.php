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

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\core\models\{Sanitizer, Session};

class LocaleSwitcher extends ModelHeader {

    private $_destination, $_params;

    public function __construct(array $params) {
        $this->loadConfig();
        $this->loadUser();
        $this->_params = $params;
    }

    public function switchLocale() : bool {
        if (!empty($_POST['code']) && empty($this->_params['code']))
            $this->_params['code'] = $_POST['code'];

        if ((empty($this->_params['code']) && empty($this->_params['article'])) || !preg_match('/([a-zA-Z]{2})\_([a-zA-Z]{2})/', $this->_params['code']))
            return false;

        $info = [
            'ip' => $_SERVER['REMOTE_ADDR'],
            'locale_code' => $this->_params['code'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $session = new Session(self::$config->read('session_name') . '_locale_pref');
        $session->create(31 * 7 * 24 * 60 * 60, false, self::$config->read('cookie_id') . '_locale_pref', $info);

        if (!self::$user->isGuest() && !empty($_POST['code']))  {
            $query = QueryFactory::produce('select', '
                SELECT id
                    FROM {db_prefix}locale
                    WHERE `code` = :code
            ');

            $query->setParams([
                'code' => $this->_params['code']
            ]);

            $result = $query->execute();
            foreach ($result as $locale) {
                self::$user->update([
                    'locale_id' => $locale['id']
                ], $errors = []);
            }
        }

        $this->_destination = self::$config->read('url');

        if (!empty($this->_params['article']))
            $this->_destination .= '/index.php/article/' . Sanitizer::addUnderscores($this->_params['article']);

        return true;
    }

    public function getDestination() : string {
        return $this->_destination;
    }
}