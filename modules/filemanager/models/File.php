<?php

namespace app\modules\filemanager\models;

use app\modules\filemanager\SimpleFilemanagerModule;
use yii\web\BadRequestHttpException;

/**
 * Class File
 * @package app\modules\filemanager\models
 * @property string $mime
 * @property string $url
 * @property Directory $directory
 */
class File extends Item
{
    public function getUrl()
    {
        return SimpleFilemanagerModule::getInstance()->urlPath . $this->path;
    }

    public function getMime()
    {
        return mime_content_type($this->fullPath);
    }

    /**
     * @return bool|string
     */
    public function getIcon()
    {
        /**
         * @var SimpleFilemanagerModule $module
         */
        $module = SimpleFilemanagerModule::getInstance();

        if (isset($module->icons[$this->mime])) {
            return $module->icons[$this->mime];
        }

        if (isset($module->icons[$this->type])) {
            return $module->icons[$this->type];
        }

        return false;
    }

    public function getDirectory()
    {
        return Directory::createByPath(dirname($this->path));
    }

    /**
     * @param string $path
     *
     * @return File
     * @throws BadRequestHttpException
     */
    public static function createByPath($path)
    {
        $file = new File();
        $file->root = SimpleFilemanagerModule::getInstance()->fullUploadPath;
        $file->path = $path;

        if ($file->type != 'file') {
            throw new BadRequestHttpException();
        }

        return $file;
    }
}