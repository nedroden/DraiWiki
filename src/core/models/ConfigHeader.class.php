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

namespace DraiWiki\src\core\models;

/**
 * Class ConfigHeader
 * This class contains the required methods for reading, manipulating and importing settings. As
 * this is an abstract class, it cannot be instantiated directly, rather it needs to be extended
 * upon.
 * @package DraiWiki\src\core\models
 * @since 1.0 Alpha 1
 */
abstract class ConfigHeader {

    private $_settings;

    public function __construct(array $settings) {
        $this->_settings = $settings;
    }

    public function read(string $identifier) {
        return !empty($this->_settings[$identifier]) ? $this->_settings[$identifier] : null;
    }

    public function import(array $settings) : void {
        foreach ($settings as $key => $value) {
            $this->_settings[$key] = $value;
        }
    }

    public function deleteDatabaseInfo() : void {
        foreach (['db_server', 'db_username', 'db_password', 'db_name'] as $setting) {
            $this->_settings[$setting] = null;
        }
    }
}
