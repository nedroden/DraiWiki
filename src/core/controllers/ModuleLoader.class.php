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

namespace DraiWiki\src\core\controllers;

use DraiWiki\src\core\models\Module;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class ModuleLoader {

    /**
     * Since modules have to be loaded as early as possible, we can't use database settings
     */
    private const MODULES_DIR = __DIR__ . '/../../../modules';

    private $_modules;
    private $_disabledModules;

    public function __construct() {
        $this->_modules = [];
        $this->_disabledModules = [];
    }

    public function scan() : void {
        $modules = scandir(self::MODULES_DIR);

        foreach ($modules as $module) {
            if ($module == '.' || $module == '..' || !is_dir(self::MODULES_DIR . '/' . $module))
                continue;

            $obj = new Module(self::MODULES_DIR . '/' . $module);

            if (!$obj->loadInfoFile(self::MODULES_DIR . '/' . $module . '/meta'))
                continue;
            else if (!$obj->isCompatible() && !$obj->getCanOverrideRequirements())
                continue;

            $this->_modules[] = $obj;
        }
    }

    public function loadAll() : void {
        foreach ($this->_modules as $module)
            $module->load();
    }

    public function canLoadModules() : bool {
        return function_exists('simplexml_load_file');
    }
}