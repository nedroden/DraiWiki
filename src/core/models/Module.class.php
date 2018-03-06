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

namespace DraiWiki\src\core\models;

use DraiWiki\src\main\controllers\Main;
use SimpleXMLElement;

if (!defined('DraiWiki')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

class Module {

    private $_dirName;
    private $_name;
    private $_mainClass;
    private $_namespace;
    private $_description;
    private $_author;
    private $_softwareVersion;
    private $_moduleVersion;
    private $_copyright;
    private $_canOverrideRequirements;

    private $_obj;

    public function __construct(string $dirName) {
        $this->_dirName = $dirName;
    }

    public function loadInfoFile(string $location) : bool {
        if (!file_exists($location . '/moduleinfo.xml'))
            return false;

        $parsed = simplexml_load_file($location . '/moduleinfo.xml', null, LIBXML_NOWARNING);

        // @todo Make log entry
        if (!$parsed)
            return false;

        return $this->parseInfoFile($parsed);
    }

    private function parseInfoFile(SimpleXMLElement $simpleXMLElement) : bool {
        // @todo Make log entry
        foreach (['name', 'main_class', 'namespace', 'software_version', 'module_version', 'copyright', 'author'] as $field)
            if (empty($simpleXMLElement->$field))
                return false;

        $this->_name = $simpleXMLElement->name;
        $this->_mainClass = $simpleXMLElement->main_class;
        $this->_namespace = $simpleXMLElement->namespace;
        $this->_description = $simpleXMLElement->description ?? '';

        $this->_softwareVersion = $simpleXMLElement->software_version;
        $this->_moduleVersion = $simpleXMLElement->module_version;
        $this->_canOverrideRequirements = (bool) $simpleXMLElement->override_requirements ?? false;

        $this->_copyright = $simpleXMLElement->copyright;
        $this->_author = $simpleXMLElement->author;

        return true;
    }

    public function load() : void {
        if (file_exists($this->_dirName . '/lock'))
            return;

        if (file_exists($autoload = $this->_dirName . '/autoload.php'))
            require_once $autoload;

        require_once $this->_dirName . '/src/controllers/' . $this->_mainClass . '.class.php';

        $className = 'DraiWiki\external\modules\\' . $this->_namespace . '\\' . $this->_mainClass;
        $this->_obj = new $className();

        $this->_obj->callHooks();
    }

    public function isCompatible() : bool {
        return Main::WIKI_VERSION == $this->_moduleVersion;
    }

    public function getName() : string {
        return $this->_name;
    }

    public function getMainClass() : string {
        return $this->_mainClass;
    }

    public function getNamespace() : string {
        return $this->_namespace;
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