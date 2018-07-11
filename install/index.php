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

// Unfortunately we can't use the autoloader here (yet!)
require_once 'Table.class.php';
require_once 'Installer.class.php';
require_once '../autoload.php';
require_once '../src/IndexEmulator.php';

$config = null;
start($config);

// Start importing
$importer = new DraiWiki\install\Installer();
$importer->loadTables();
$importer->start();