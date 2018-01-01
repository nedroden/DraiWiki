<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   2017-2018, DraiWiki
 * @license     Apache 2.0
 */

namespace DraiWiki\src\tools\models;

if (!defined('DraiWiki')) {
    header('Location: ../index.php');
    die('You\'re really not supposed to be here.');
}

use DraiWiki\src\auth\models\User;
use DraiWiki\src\core\controllers\QueryFactory;
use DraiWiki\src\errors\CantProceedException;
use DraiWiki\src\main\models\ModelHeader;

class Image extends ModelHeader {

    private $_id;
    private $_extension;
    private $_useGD;
    private $_tempName, $_originalName, $_uploadedName;
    private $_type, $_destination, $_destinationUrl;
    private $_description;
    private $_user;
    private $_uploadDate;

    private const DEFAULT_IMAGE_TYPE = 'uploaded_image';

    public function __construct(string $type = self::DEFAULT_IMAGE_TYPE, string $originalName, ?string $tempName = '', ?string $description = '') {
        $this->loadConfig();
        $this->loadUser();

        $this->_originalName = $originalName;
        $this->_description = $description ?? '';

        if (!empty($tempName)) {
            $this->_tempName = $tempName;
            $this->_extension = pathinfo($originalName, PATHINFO_EXTENSION);
        }

        $this->setType($type);

        if (!empty($tempName))
            $this->existingFileCheck();

        $this->_useGD = self::$config->read('gd_image_upload') == 1 && function_exists('imagecreatefromgif');
    }

    public function upload(array &$errors) : void {
        $destination = $this->_useGD ? self::$config->read('path') . '/temp' : $this->_destination;
        $filename = $this->generateFileName();
        $uploadedFileName = $destination . '/' . $filename . '.' . $this->_extension;

        if ($this->_useGD) {
            switch ($this->_extension) {
                case 'gif':
                    $image = imagecreatefromgif($uploadedFileName);
                    break;
                case 'png':
                    $image = imagecreatefrompng($uploadedFileName);
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($uploadedFileName);
                    break;
                default:
                    $image = rename($uploadedFileName, $this->_destination . '/' . $filename . '.' . $this->_extension);
                    $noFunction = true;
            }

            if (!$image) {
                $errors[] = self::$locale->read('tools', 'could_not_move_image');
                return;
            }

            if (empty($noFunction)) {
                $imgFile = @fopen($this->_destination . '/' . $filename . '.' . $this->_extension, 'w');

                if ($imgFile) {
                    fwrite($imgFile, $image);
                    fclose($imgFile);

                    @unlink($uploadedFileName);
                }
                else {
                    $errors[] = self::$locale->read('tools', 'could_not_move_image');
                    return;
                }
            }
        }

        else {
            $result = @move_uploaded_file($this->_tempName, $uploadedFileName);

            if (!$result) {
                $errors[] = self::$locale->read('tools', 'could_not_upload_image');
                return;
            }
        }

        $query = QueryFactory::produce('modify', '
            INSERT
                INTO {db_prefix}upload (
                    original_name, uploaded_name, description, user_id, type, upload_date
                )
                VALUES (
                    :original_name,
                    :uploaded_name,
                    :description,
                    :uid,
                    :type,
                    NOW()
                )
        ');

        $query->setParams([
            'original_name' => $this->_originalName,
            'uploaded_name' => $filename . '.' . $this->_extension,
            'description' => $this->_description ?? null,
            'uid' => self::$user->getID(),
            'type' => $this->_type
        ]);

        $query->execute();
    }

    public function load() : bool {
        $query = QueryFactory::produce('select', '
            SELECT id, original_name, uploaded_name, description, user_id, upload_date
                FROM {db_prefix}upload
                WHERE original_name = :original_name
        ');

        $query->setParams([
            'original_name' => $this->_originalName
        ]);

        $result = $query->execute();

        if (!is_array($result) || count($result) == 0)
            return false;

        foreach ($result as $image) {
            $this->_id = (int) $image['id'];
            $this->_originalName = $image['original_name'];
            $this->_uploadedName = $image['uploaded_name'];
            $this->_description = $image['description'];
            $this->_user = $image['user_id'] == self::$user->getID() ? self::$user : new User($image['user_id']);
            $this->_uploadDate = date($image['upload_date']);
            return true;
        }

        return false;
    }

    private function generateFileName() : string {
        while (true) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $fileName = '';

            for ($i = 0; $i < 40; $i++)
                $fileName .= $characters[rand(0, strlen($characters) - 1)];

            if (!file_exists($this->_destination . '/' . $fileName . $this->_extension))
                return $fileName;
        }

        return null;
    }

    private function setType(string $type) : void {
        $types = ['avatar' => 'avatars', 'uploaded_image' => 'images'];

        $this->_type = array_key_exists($type, $types) ? $type : self::DEFAULT_IMAGE_TYPE;
        $this->_destination = self::$config->read('path') . '/public/uploads/' . $types[$this->_type];
        $this->_destinationUrl = self::$config->read('url') . '/public/uploads/' . $types[$this->_type];
    }

    private function existingFileCheck() : void {
        $files = [];

        $query = QueryFactory::produce('select', '
            SELECT original_name
                FROM {db_prefix}upload
                WHERE original_name LIKE CONCAT(:filename, \'%\')
        ');

        $query->setParams([
            'filename' => $this->_originalName
        ]);

        $result = $query->execute();

        foreach ($result as $file)
            $files[] = $file['original_name'];

        if (in_array($this->_originalName, $files) && !in_array($this->_originalName . ' (1)', $files)) {
            $this->_originalName .= ' (1)';
            return;
        }
        else if (!in_array($this->_originalName, $files))
            return;

        for ($i = 2; $i < PHP_INT_MAX; $i++) {
            if (!in_array($filename = $this->_originalName . ' (' . $i . ')', $files)) {
                $this->_originalName = $filename;
                return;
            }
        }

        (new CantProceedException('could_not_assign_name'))->trigger();
    }

    public function getID() : int {
        return $this->_id;
    }

    public function getOriginalName() : string {
        return $this->_originalName;
    }

    public function getUploadedName() : string {
        return $this->_uploadedName;
    }

    public function getDescription() : ?string {
        return $this->_description;
    }

    public function getPath() : string {
        return $this->_destination . '/' . $this->_uploadedName;
    }

    public function getUrl() : string {
        return $this->_destinationUrl . '/' . $this->_uploadedName;
    }

    public function getUser() : User {
        return $this->_user;
    }

    public function getUploadDate() : string {
        return $this->_uploadDate;
    }

    public function getExtension() : string {
        return pathinfo($this->_originalName, PATHINFO_EXTENSION);
    }

    public function getUploadedExtension() : string {
        return pathinfo($this->_uploadedName, PATHINFO_EXTENSION);
    }
}