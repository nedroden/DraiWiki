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

define('DraiWiki', 1);

// Unfortunately we can't use the autoloader here (yet!)
require_once 'Table.class.php';
require_once '../autoload.php';
require_once './../src/IndexEmulator.php';

class Importer {

    private $_tables;

    public function __construct() {
        if (!function_exists('simplexml_load_file'))
            die('Could not detect the SImpleXML extension. Please make sure it\'s enabled');

        $this->_tables = [];

        echo '<h1>Database importer</h1>';
    }

    public function showStatusMessage(string $message, bool $status = null) : void {
        if ($status !== null)
            echo $message, '... ', ($status ? '[<span style="color: #27d829;">OK</span>]' : '[<span style="color: #d82727;">FAILED</span>]'), '<br />';
        else
            echo $message, '<br />';
    }

    public function loadTables() : void {
        $files = scandir(__DIR__ . '/../tables');

        foreach ($files as $file) {
            if (substr($file, -4) != '.xml' || is_dir(__DIR__ . '/../tables/' . $file))
                continue;

            $parsedFile = simplexml_load_file(__DIR__ . '/../tables/' . $file);
            $this->_tables[] = new Table($parsedFile);
        }
    }

    public function start() : void {
        foreach ($this->_tables as $table) {
            $errors = [];
            $table->validate($errors);

            if (!empty($errors)) {
                $this->showStatusMessage('Attempting to update table ' . $table->getName(), false);
                break;
            }

            if (!$table->checkIfExists())
                $table->create();
            else
                $table->update();
        }
    }
}

// Start index emulation
$config = null;
$connection = null;
start($config);
connectToDatabase($connection);

// Start importing
$importer = new Importer();
$importer->loadTables();
$importer->start();