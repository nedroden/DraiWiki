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

namespace DraiWiki\src\main\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;

class Table extends ModelHeader {

    private $_columns, $_data, $_gui, $_localeFile, $_view;

    private $_id, $_type;

    public function __construct(string $localeFile, array $columns, ?array $data = []) {
        $this->_columns = $columns;
        $this->_data = $data;

        $this->loadLocale();
        self::$locale->loadFile($localeFile);
        $this->_localeFile = $localeFile;

        $this->_gui = Registry::get('gui');
        $this->_type = 'crud_table';
    }

    public function create() : void {
        foreach ($this->_columns as &$column)
            $column = self::$locale->read($this->_localeFile, $column);

        $table = [
            'id' => $this->_id ?? 'my_table',
            'type' => $this->_type,
            'columns' => $this->_columns,
            'rows' => $this->_data
        ];

        $template = $this->_type == 'form' ? 'table_form' : 'table';

        $this->_view = $this->_gui->parseAndGet($template, $table, false);
    }

    public function returnTable() : string {
        return $this->_view;
    }

    public function printTable() : void {
        echo $this->_view;
    }

    public function setType(string $type) : void {
        $this->_type = $type;
    }

    public function setID(string $id) : void {
        $this->_id = $id;
    }
}