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

namespace DraiWiki\src\auth\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\{QueryFactory, Registry};

/**
 * This class is used for generating, verifying and deleting user activation codes.
 * @author DraiWiki development team
 * @since 1.0 Alpha 1
 * @package DraiWiki\src\auth\models
 */
class ActivationCode {

    /**
     * @var Config $_config The configuration class
     */
    private $_config;

    /**
     * @var int $_uid The user ID
     */
    private $_uid;

    /**
     * @var string $_code The activation code. It has either already been created or will be created.
     */
    private $_code;

    /**
     * @var string $_link An activation link. This is used in activation emails
     */
    private $_link;

    /**
     * Create an new activation code
     * @param int $uid The user ID
     */
    public function __construct(int $uid = 0) {
        $this->_uid = $uid;
        $this->_config = Registry::get('config');
    }

    /**
     * Adds an activation code to the database
     * @return void
     */
    private function insertCode() : void {
        $query = QueryFactory::produce('modify', '
            INSERT
                INTO {db_prefix}activation_code (
                    user_id,
                    `code`
                )
                VALUES (
                    :uid,
                    :activation_code
                )
        ');

        $query->setParams([
            'uid' => $this->_uid,
            'activation_code' => $this->_code
        ]);

        $query->execute();
    }

    /**
     * Generates and sets the activation link to be used in emails
     * @return void
     */
    private function generateLink() : void {
        $this->_link = $this->_config->read('url') . '/index.php/activate/' . $this->_code;
    }

    /**
     * Generates and sets the activation code
     * @return void
     */
    public function generateCode() : void {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $code = '';

        for ($i = 0; $i < 20; $i++)
            $code .= $characters[rand(0, strlen($characters) - 1)];

        $this->_code = $code;
        $this->insertCode();
        $this->generateLink();
    }

    /**
     * Loads an existing activation code from the database
     * @return void
     */
    public function loadCode() : void {
        $query = QueryFactory::produce('select', '
            SELECT activation_code
                FROM {db_prefix}activation_code
                WHERE user_id = :uid
                AND creation_date > (NOW() - :activation_code_length)
                ORDER BY creation_date DESC
                LIMIT 1
        ');

        $query->setParams([
            'uid' => $this->_uid,
            'activation_code_length' => $this->_config->read('activation_code_length') * 60 * 60
        ]);

        $result = $query->excute();

        foreach ($result as $record)
            $this->_code = $record['activation_code'];

        if (empty($result))
            $this->_code = '';

        $this->generateLink();
    }

    /**
     * Deletes a single activation code from the database (based on the value of $_code)
     * @return void
     */
    public function deleteCode() : void {
        $query = QueryFactory::produce('modify', '
            DELETE
                FROM {db_prefix}activation_code
                WHERE activation_code = :activation_code
                AND uid = :uid
                LIMIT 1
        ');

        $query->setParams([
            'activation_code' => $this->_code,
            'uid' => $this->_uid
        ]);

        $query->execute();
    }

    /**
     * Deletes all activation codes that belong to a particular user.
     * @return void
     */
    public function deleteAllFromUser() : void {
        $query = QueryFactory::produce('modify', '
            DELETE
                FROM {db_prefix}activation_code
                WHERE uid = :uid
        ');

        $query->setParams(['uid' => $this->_uid]);
        $query->execute();
    }

    /**
     * Compare an activation code entered by the user to the most recent verification code in the
     * database.
     * @param string $code The activation code entered by the user
     * @return bool
     */
    public function verify(string $code) : bool {
        return $code == $this->_code;
    }

    /**
     * @return string $_code The activation code
     */
    public function getCode() : string {
        return $this->_code;
    }

    /**
     * @return string $_link The activation link
     */
    public function getLink() : string {
        return $this->_link;
    }
}