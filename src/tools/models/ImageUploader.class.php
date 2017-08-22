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

use DraiWiki\src\core\models\InputValidator;
use DraiWiki\src\main\models\ModelHeader;

class ImageUploader extends ModelHeader {

    private $_image, $_imageDescription;

    private const MAX_FILENAME_LENGTH = 100;

    public function __construct() {
        $this->loadLocale();
        $this->loadConfig();

        self::$locale->loadFile('tools');
    }

    public function getTitle() : string {
        return self::$locale->read('tools', 'upload_an_image');
    }

    public function prepareData() : array {
        return [
            'action' => self::$config->read('url') . '/index.php/imageupload'
        ];
    }

    public function validate(array &$errors) : void {
        if (empty($_FILES['file']) || empty($_FILES['file']['name'])) {
            $errors['file'] = self::$locale->read('tools', 'no_image_chosen');
            return;
        }

        if (!empty($_FILES['file']['error'])) {
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors['file'] = self::$locale->read('tools', 'file_denied_size');
                    return;
                default:
                    $errors['file'] = self::$locale->read('tools', 'file_denied_unknown');
                    return;
            }
        }

        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (!in_array($extension, explode(';', self::$config->read('allowed_image_extensions'))))
            $errors['file'] = self::$locale->read('tools', 'extension_not_accepted');
        else if ($_FILES['file']['size'] > ((int) self::$config->read('max_image_size_kb') * 1024 * 1024))
            $errors['file'] = self::$locale->read('tools', 'file_denied_size');
        else if (self::$config->read('gd_image_upload') == 1 && !getimagesize($_FILES['file']['tmp_name']))
            $errors['file'] = self::$locale->read('tools', 'not_an_image');

        $validator = new InputValidator($_FILES['file']['name']);
        if ($validator->containsHTML())
            $errors['file'] = self::$locale->read('tools', 'image_name_html');
        else if ($validator->isTooLong(self::MAX_FILENAME_LENGTH))
            $errors['file'] = sprintf(self::$locale->read('tools', 'filename_too_long'), self::MAX_FILENAME_LENGTH);

        if (!empty($_POST['description'])) {
            $validator = new InputValidator($_POST['description']);

            if ($validator->isTooLong($max = (int) self::$config->read('max_image_description_length')))
                $errors['description'] = sprintf(self::$Locale->read('tools', 'description_too_long'), $max);

            $this->_imageDescription = $_POST['description'];
        }
    }

    public function upload(array &$errors) : void {
        $this->_image = new Image('uploaded_image', $_FILES['file']['name'], $_FILES['file']['tmp_name'], ($this->_imageDescription ?? null));
        $this->_image->upload($errors);
    }
}