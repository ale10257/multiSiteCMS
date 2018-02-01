<?php

namespace app\core\workWithFiles;

use yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

trait UploadFiles
{
    /**
     * @param $web_dir
     * @param $field_name
     * @return string|bool
     * @throws Exception
     */
    public function uploadOneFile($web_dir, $field_name)
    {
        $file = UploadedFile::getInstance($this, $field_name);

        if ($file && $file->tempName) {
            $path = $this->createPath($web_dir);
            $file_name = uniqid() . '.' . $file->extension;
            if (!$file->saveAs($path . $file_name)) {
                throw new \DomainException('Error save file ' . $path . $file_name);
            }
            return $file_name;
        }

        return false;
    }

    /**
     * @param $web_dir
     * @param string $field_name
     * @return array|bool
     * @throws Exception
     */
    public function uploadAnyFile($web_dir, $field_name)
    {
        $files = UploadedFile::getInstances($this, $field_name);
        $images = [];

        $path = $this->createPath($web_dir);

        if ($files && $files[0]->tempName) {
            foreach ($files as $file) {
                /**@var $file UploadedFile */
                $file_name = uniqid() . '.' . $file->extension;
                if (!$file->saveAs($path . $file_name)) {
                    throw new \DomainException('Error save file ' . $path . $file_name);
                }
                $images[] = $file_name;
            }
            return $images;
        }

        return false;
    }

    /**
     * @param string $web_dir
     * @return string
     * @throws Exception
     */
    private function createPath(string $web_dir)
    {
        $path = FileHelper::normalizePath(yii::getAlias('@webroot')) . $web_dir;

        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        return $path;
    }
}
