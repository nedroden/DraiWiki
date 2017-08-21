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

require __DIR__ . '/../src/IndexEmulator.php';

if (empty($_GET['filename']))
    die;

// Variables
$config = null;
$connection = null;

start($config);
connectToDatabase($connection);