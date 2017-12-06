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

namespace DraiWiki\src\core\models;

use DraiWiki\src\main\controllers\Main;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Module {

    private $_dirName;
    private $_name;
    private $_description;
    private $_author;
    private $_softwareVersion;
    private $_moduleVersion;
    private $_copyright;
    private $_canOverrideRequirements;

    public function __construct(string $dirName) {
        $this->_dirName = $dirName;
    }

    public function loadInfoFile(string $location) : bool {
        if (!file_exists($location . '/moduleinfo.xml'))
            return false;

        return true;
    }

    public function load() : void {
        // pass
    }

    public function isCompatible() : bool {
        return Main::WIKI_VERSION == $this->_moduleVersion;
    }

    public function getName() : string {
        return $this->_name;
    }

    public function getDescription() : string {
        return $this->_description;
    }

    public function getAuthor() : string {
        return $this->_author;
    }

    public function getSoftwareVersion() : string {
        return $this->_softwareVersion;
    }

    public function getModuleVersion() : string {
        return $this->_moduleVersion;
    }

    public function getCopyright() : string {
        return $this->_copyright;
    }

    public function getCanOverrideRequirements() : bool {
        return $this->_canOverrideRequirements ?? false;
    }
}