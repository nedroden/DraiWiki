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

use DraiWiki\src\tools\models\Image;

require __DIR__ . '/../src/IndexEmulator.php';

/* This constant only applies to image LOOKUPS. The upload process uses
   its own constant. The reason for this is simple: imagine that at one
   time you could upload files with titles up to 300 characters. Then,
   you decided to decrease that number to 200. Old images that no longer
   met this requirement could then no longer be accessed. */
const MAX_FILENAME_LENGTH = 250;

if (empty($_GET['filename']))
    die;
else if (strlen($_GET['filename']) > MAX_FILENAME_LENGTH)
    die('Filename too long.');

$config = null;
$connection = null;

start($config);
connectToDatabase($connection);
loadModules();
loadEnvironment();

$image = new Image('image', $_GET['filename']);
$image->load();

$mimeTypes = require_once($config->read('path') . '/src/MimeTypes.php');
if (empty($mimeTypes[$image->getUploadedExtension()]))
    die('Could not determine image mime type');

if (!file_exists($image->getPath()))
    die('File not found.');

// Display the image
header('Content-Type: ' . $mimeTypes[$image->getUploadedExtension()]);
echo readfile($image->getPath());