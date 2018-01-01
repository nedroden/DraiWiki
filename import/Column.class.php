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

namespace DraiWiki\import;

use SimpleXMLElement;

class Column {

    private $_tableName;

    private $_name;
    private $_type;
    private $_isUnsigned;
    private $_autoIncrement;
    private $_isPrimaryKey;
    private $_canBeNull;

    /**
     * Column constructor. Since fields like 'incr' and 'is_pkey' should have a binary value (0 or 1), we
     * can safely cast them to bool (0 = false, 1 = true).
     * @param string $tableName
     * @param SimpleXMLElement $xmlElement
     */
    public function __construct(string $tableName, SimpleXMLElement $xmlElement) {
        // Table-related data
        $this->_tableName = $tableName;

        // Column-related data
        $this->_name = $xmlElement['name'];
        $this->_type = $xmlElement['type'] ?? 'text';
        $this->_isUnsigned = (bool) $xmlElement['unsigned'] ?? 0;
        $this->_autoIncrement = (bool) $xmlElement['incr'] ?? 0;
        $this->_isPrimaryKey = (bool) $xmlElement['is_pkey'] ?? 0;
        $this->_canBeNull = (bool) $xmlElement['null'] ?? 0;
    }

    public function getName() : string {
        return $this->_name;
    }

    public function getType() : string {
        return $this->_type;
    }

    public function getUnsigned() : bool {
        return $this->_isUnsigned;
    }

    public function getAutoIncrement() : bool {
        return $this->_autoIncrement;
    }

    public function getIsPrimaryKey() : bool {
        return $this->_isPrimaryKey;
    }

    public function getCanBeNull() : bool {
        return $this->_canBeNull;
    }
}