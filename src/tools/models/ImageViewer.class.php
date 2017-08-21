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

namespace DraiWiki\src\tools\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\main\models\ModelHeader;
use DraiWiki\src\main\models\Table;
use Parsedown;

class ImageViewer extends ModelHeader {

    private $_image, $_parsedown, $_table;

    public function __construct(string $filename) {
        $this->loadLocale();
        $this->_image = new Image('image', $filename);

        self::$locale->loadFile('tools');

        $this->_parsedown = new Parsedown();
    }

    public function loadImage() : bool {
        return $this->_image->load();
    }

    public function prepareData(): array {
        $this->generateTable();

        return [
            'title' => $this->_image->getOriginalName(),
            'url' => $this->_image->getUrl(),
            'description' => $this->_parsedown->setMarkupEscaped(true)->text($this->_image->getDescription()),
            'table' => $this->_table->returnTable()
        ];
    }

    public function getTitle() : string {
        return $this->_image->getOriginalName();
    }

    private function generateTable() : void {
        $this->_table = new Table(
            'tools',
            [
                'key',
                'value'
            ],
            [
                [self::$locale->read('tools', 'image_filename'), $this->_image->getOriginalName()],
                [self::$locale->read('tools', 'poster'), $this->_image->getUser()->getUsername()],
                [self::$locale->read('tools', 'upload_date'), $this->_image->getUploadDate()]
            ]
        );

        $this->_table->setID('image_info');
        $this->_table->setType('info_table');
        $this->_table->create();
    }
}