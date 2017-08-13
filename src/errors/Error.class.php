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

namespace DraiWiki\src\errors;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\core\controllers\Registry;
use DraiWiki\src\core\models\Sanitizer;

abstract class Error {

    protected $detailedInfo;
    protected $locale;

    public function __construct(string $detailedInfo) {
        ob_clean();

        $this->detailedInfo = $detailedInfo;

        $this->locale = Registry::get('locale', true);
        $this->hasLocale = $this->locale != NULL;
    }

    protected function canViewDetailedInfo() : bool {
        return true;
    }

    protected function generateMessage() : array {
        return [
            'title' => $this->hasLocale ? $this->locale->read('error', 'something_went_wrong') : 'Something went wrong',
            'body' => $this->hasLocale ? $this->locale->read('error', 'generic_error_message') : 'An error occurred. Please contact the administrator.',
            'detailed' => $this->canViewDetailedInfo() ? $this->detailedInfo : NULL
        ];
    }

    protected function getBacktrace() : array {
        $backtrace = debug_backtrace();
        $parsedBacktrace = [];

        $counter = 1;
        for ($i = count($backtrace) - 1; $i > 2; $i--) {
            $detail = $backtrace[$i];

            if (!empty($detail['args'])) {
                foreach ($detail['args'] as &$arg) {
                    if (is_array($arg))
                        $arg = 'array';

                    $arg = Sanitizer::nullToString('NULL', $arg);
                }
            }

            $parsedBacktrace[] = $counter++ . '. 
                <strong>[' . ($detail['file'] ?? 'unknown') . ']</strong> 
                Called method <em>' . ($detail['function'] ?? 'unknown') . '(' . (implode(',', $detail['args']) ?? '') .  ')</em> 
                on line ' . ($detail['line'] ?? 'unknown');
        }

        return $parsedBacktrace;
    }
}