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

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\main\models\Activity;

function _localized(string $identifier, ...$args) : string {
    $locale = Registry::get('locale');
    $set = explode('.', $identifier);

    // This Locale class will take care of this
    if (count($set) != 2)
        return $locale->read('unknown', $identifier);

    if (!$locale->hasLoadedFile($set[0]))
        $locale->loadFile($set[0]);

    $str = $locale->read($set[0], $set[1]);
    return empty($args) ? $str : vsprintf($str, $args);
}

function _logAction(int $type, ?int $articleId, ?User $user = null) : void {
    $locale = Registry::get('locale')->getCurrentLocaleInfo()->getID();
    $userId = ($user ?? Registry::get('user'))->getID();

    Activity::add($userId, $locale, $articleId, $type);
}