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

namespace DraiWiki\import;

use DraiWiki\src\core\controllers\QueryFactory;
use SimpleXMLElement;

class Table {

    private $_name;
    private $_type;
    private $_isUnsigned;
    private $_autoIncrement;
    private $_isPrimaryKey;
    private $_canBeNull;

    public function __construct(SimpleXMLElement $xmlElement) {
        $this->_name = $xmlElement['name'];
        $this->_type = $xmlElement['type'] ?? 'text';
        $this->_isUnsigned = $xmlElement['unsigned'] ?? 0;
        $this->_autoIncrement = $xmlElement['incr'] ?? 0;
        $this->_isPrimaryKey = $xmlElement['is_pkey'] ?? 0;
        $this->_canBeNull = $xmlElement['null'] ?? 0;
    }

    public function checkIfExists() : bool {
        $query = QueryFactory::produce('select', '
            
        ');
    }

    public function create() : void {

    }

    public function update() : void {

    }

    public function validate(array &$errors) : void {

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