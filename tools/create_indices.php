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

$filename = 'index.php';
$file = dirname(__FILE__, 2) . '/src/' . $filename;

$countSuccess = 0;
$countFailed = 0;

function copyIndex($dir) {
    global $file, $filename, $countSuccess, $countFailed;

    if (!is_dir($dir))
        return;

    $directories = scandir($dir);

    foreach ($directories as $directory) {
        if ($directory == '.' || $directory == '..')
            continue;

        else
            copyIndex($dir . '/' . $directory);
    }

    if ($dir != dirname(__FILE__, 2)) {
        echo 'Attempting to copy file to ', $dir, '... ';

        if (!file_exists($dir . '/index.html') && !file_exists($dir . '/index.php')) {
            if (isset($_GET['debug']))
                $result = copy($file, $dir . '/' . $filename);
            else
                $result = @copy($file, $dir . '/' . $filename);

            if (!$result) {
                echo '[<span style="color: #d82727;">FAILED</span>]<br />';
                $countFailed++;
            }
            else {
                echo '[<span style="color: #27d829;">OK</span>]<br />';
                $countSuccess++;
            }
        }

        else
            echo '[<span style="color: #79a3aa;">SKIP</span>]<br />';
    }
}

echo '<strong>Preparing to copy file (', $file, ')...</strong><br />';

copyIndex(dirname(__FILE__, 2));

echo '<strong>Successfully copied ', $countSuccess, ' files.', ($countFailed != 0 ? ' ' . $countFailed . ' files could not be copied.' : '');