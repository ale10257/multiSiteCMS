<?php

namespace app\modules\filemanager;

use Yii;
use yii\base\Module;
use yii\helpers\FileHelper;

/**
 * Class SimpleFilemanagerModule
 * @package app\modules\filemanager
 * @property string $fullUploadPath
 * @property string $urlPath
 */
class SimpleFilemanagerModule extends Module
{
    const BASE_DIR = 'files';

    /** @var string  */
    public $controllerNamespace;
    /** @var array  */
    public $icons = [];
    /** @var array  */
    public $defaultIcons = [
        'dir' => 'fa-folder-o',
        'file' => 'fa-file-o',
        'image/gif' => 'fa-file-image-o',
        'image/tiff' => 'fa-file-image-o',
        'image/png' => 'fa-file-image-o',
        'image/jpeg' => 'fa-file-image-o',
        'application/pdf' => 'fa-file-pdf-o',
        'application/zip' => 'fa-file-archive-o',
        'application/x-gzip' => 'fa-file-archive-o',
        'text/plain' => 'fa-file-text-o',
    ];

    /** @var string */
    private $_uploadPath;
    /** @var string  */
    private $_urlPath;

    /**
     * @throws \yii\base\Exception
     */
    public function init()
    {
        parent::init();

        $this->controllerNamespace = 'app\modules\filemanager\controllers';
        $this->_urlPath = yii::getAlias('@web/' . UPLOAD_DIR . '/' . self::BASE_DIR);
        $webroot = FileHelper::normalizePath(yii::getAlias('@webroot'));
        $this->_uploadPath = $webroot . DIRECTORY_SEPARATOR . UPLOAD_DIR . DIRECTORY_SEPARATOR . self::BASE_DIR;
        $this->_checkPath();

        $this->icons = array_merge($this->defaultIcons, $this->icons);

        if (!isset(\Yii::$app->i18n->translations['filemanager'])) {
            \Yii::$app->i18n->translations['filemanager'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => $this->basePath . DIRECTORY_SEPARATOR . 'messages',
                'fileMap' => ['filemanager' => 'filemanager.php'],
            ];
        }
    }

    /**
     * @return string
     */
    public function getFullUploadPath()
    {
        if (!$this->_uploadPath) {
            throw new \DomainException('Upload directory not found!');
        }

        return $this->_uploadPath;
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        if (!$this->_urlPath) {
            throw new \DomainException('Web address for file not found!');
        }

        return $this->_urlPath;
    }

    /**
     * @throws \yii\base\Exception
     */
    private function _checkPath()
    {
        if (!is_dir($this->_uploadPath)) {
            FileHelper::createDirectory($this->_uploadPath);
        }
    }
}