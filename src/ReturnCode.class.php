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

namespace DraiWiki;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

class ReturnCode {

    public const SUCCESS = 0x00;
    public const GENERAL_ERROR = 0x01;
    public const PERMISSION_DENIED = 0x02;
    public const RECORD_NOT_FOUND = 0x03;
    public const INVALID_ACTION = 0x04;
    public const TEMPLATE_NOT_FOUND = 0x05;
    public const NOT_IMPLEMENTED = 0xFF;
}