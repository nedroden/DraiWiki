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

define('DraiWiki', 1);

use DraiWiki\src\main\models\Stylesheet;

include 'public/Config.php';

if (!empty($_GET['id'])) {
	require_once __DIR__ . '/src/main/models/Stylesheet.class.php';

	$stylesheet = new Stylesheet($_GET['id']);
	echo $stylesheet->parse();
	die;
}
