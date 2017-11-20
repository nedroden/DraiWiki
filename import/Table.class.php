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
    private $_columns;
    private $_charset;

    /**
     * @todo Replace with user-defined variable
     */
    private const DB_PREFIX = 'drai_';

    public function __construct(SimpleXMLElement $xmlElement) {
        $this->_name = $xmlElement['name'];
        $this->_charset = $xmlElement['charset'];

        $this->_columns = [];

        foreach ($xmlElement['columns'] as $column)
            $this->_columns[] = new Column($this->_name, $column);
    }

    public function checkIfExists() : bool {
        $query = QueryFactory::produce('select', '
            SHOW TABLES LIKE :name
        ');

        $query->setParams([
            'name' => self::DB_PREFIX . $this->_name
        ]);

        return count($query->execute()) > 0;
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
}