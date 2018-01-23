<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.11.17
 * Time: 17:14
 */

namespace app\core\workWithFiles;

use yii;
use yii\helpers\FileHelper;

/**
 * Class ImgThumb
 * @package app\core\workWithFiles
 *
 * @property int $width
 * @property int $height
 * @property bool $crop
 * @property bool $orientation
 * @property string $thumb_dir
 * @property string $web_dir
 */

class ImgThumb
{
    /**
     * @var int
     */
    private $width = 200;

    /**
     * @var int
     */
    private $height = 150;

    /**
     * @var bool
     */
    private $crop = false;

    /**
     * @var bool
     */
    private $orientation = false;

    /**
     * @var string
     */
    private $thumb_dir = 'thumb';

    /**
     * @var string
     */
    private $web_dir;

    /**
     * @param $property
     * @param $value
     * @throws yii\base\Exception
     */
    public function __set($property, $value)
    {
        if ($property != 'web_dir') {
            if(!property_exists($this, $property)) {
                throw new \DomainException('Unknown property!');
            }
            if ($property) {
                $this->$property = $value;
            }
        } else {
            $web_dir = FileHelper::normalizePath($value);
            $path = FileHelper::normalizePath(yii::getAlias('@webroot'));
            if (!is_dir($path . $web_dir)) {
                FileHelper::createDirectory($path . $web_dir);
            }
            $this->web_dir = $web_dir;
        }
    }

    /**
     * @param string $file
     * @return DataPathImage|bool
     * @throws \ImagickException
     * @throws yii\base\Exception
     */
    public function checkFile(string $file)
    {
        if (!$this->web_dir) {
            return false;
        }

        $file_check = FileHelper::normalizePath(yii::getAlias('@webroot')) . $this->web_dir . DIRECTORY_SEPARATOR . $file;

        if (!is_file($file_check)) {
            return false;
        }

        if ($this->orientation) {
            list($w, $h) = getimagesize($file_check);
            if ($h > $w) {
                if (!$this->crop && $this->orientation) {
                    $old_width = $this->width;
                    $this->width = $this->height;
                    $this->height = $old_width;
                } else {
                    $this->width = $this->width / ($this->width / $this->height);
                }
            }
        }

        $thumb_dir = FileHelper::normalizePath( yii::getAlias('@webroot')) . $this->web_dir . DIRECTORY_SEPARATOR . $this->thumb_dir;

        if (!is_dir($thumb_dir)) {
            FileHelper::createDirectory($thumb_dir);
        }

        $thumb_file = $thumb_dir . DIRECTORY_SEPARATOR . $file;

        $dataPathImage = new DataPathImage();

        $this->web_dir = str_replace('\\', '/', $this->web_dir);

        if (is_file($thumb_file)) {
            list($w, $h) = getimagesize($thumb_file);
            if ($w == $this->width && $h == $this->height) {
                $dataPathImage->webPath = $this->web_dir . '/' . $file;
                $dataPathImage->webThumbPath = $this->web_dir . '/' . $this->thumb_dir . '/' . $file;
                return $dataPathImage;
            }
        }
        if ($this->copyResizeImage($file_check, $thumb_file)) {
            $dataPathImage->webPath = $this->web_dir . '/' . $file;
            $dataPathImage->webThumbPath = $this->web_dir . '/' . $this->thumb_dir . '/' . $file;
            return $dataPathImage;
        }

        return false;
    }

    /**
     * @param $inputFile
     * @param $outputFile
     * @return bool
     */
    private function copyResizeImage($inputFile, $outputFile)
    {
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            if (extension_loaded('imagick')) {
                try {
                    $image = new \Imagick($inputFile);
                    if ($this->height && !$this->crop) {
                        $image->resizeImage($this->width, $this->height, \Imagick::FILTER_LANCZOS, 1, true);
                    }
                    if ($this->height && $this->crop) {
                        $image->cropThumbnailImage($this->width, $this->height);
                    }
                    return $image->writeImage($outputFile);
                } catch (\Exception $e) {
                    return $this->createFromGD($inputFile, $outputFile);
                }
            } else {
                return $this->createFromGD($inputFile, $outputFile);
            }
        } else {
            throw new \DomainException('Please install GD or Imagick extension');
        }
    }

    /**
     * @param $inputFile
     * @param $outputFile
     * @return bool
     */
    private function createFromGD($inputFile, $outputFile)
    {
        $image = new GD($inputFile);

        if ($this->height) {
            if ($this->width && $this->crop) {
                $image->cropThumbnail($this->width, $this->height);
            } else {
                $image->resize($this->width, $this->height);
            }
        } else {
            $image->resize($this->width);
        }

        return $image->save($outputFile);
    }


}
