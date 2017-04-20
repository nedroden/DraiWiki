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
ob_start();

set_time_limit(25);

use DraiWiki\admin\controllers\Admin;

define('DWA', 1);

require __DIR__ . '/controllers/Admin.class.php';

$admin = new Admin(__DIR__);
$admin->display();
