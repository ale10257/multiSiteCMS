<?php
namespace app\core\workWithFiles;

class GD
{
    /** @var resource  */
    private $image;
    /** @var string  */
    private $mime;
    /** @var int */
    private $width;
    /** @var int */
    private $height;

    public function __construct($file)
    {
        if (file_exists($file)) {
            $imageData = getimagesize($file);
            $this->mime = image_type_to_mime_type($imageData[2]);
            $this->width = $imageData[0];
            $this->height = $imageData[1];

            switch ($this->mime) {
                case 'image/jpeg':
                    $this->image = imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                    $this->image = imagecreatefrompng($file);
                    break;
                case 'image/gif':
                    $this->image = imagecreatefromgif($file);
                    break;
            }
        }
    }

    public function resize($width = null, $height = null)
    {
        if (!$this->image || (!$width && !$height)) {
            return false;
        }

        if (!$width) {
            if ($this->height > $height) {
                $ratio = $this->height / $height;
                $newWidth = round($this->width / $ratio);
                $newHeight = $height;
            } else {
                $newWidth = $this->width;
                $newHeight = $this->height;
            }
        } elseif (!$height) {
            if ($this->width > $width) {
                $ratio = $this->width / $width;
                $newWidth = $width;
                $newHeight = round($this->height / $ratio);
            } else {
                $newWidth = $this->width;
                $newHeight = $this->height;
            }
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resizedImage, false);

        imagecopyresampled(
            $resizedImage,
            $this->image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $this->width,
            $this->height
        );

        $this->image = $resizedImage;
    }

    public function cropThumbnail($width, $height)
    {
        if (!$this->image || !$width || !$height) {
            return false;
        }

        $sourceRatio = $this->width / $this->height;
        $thumbRatio = $width / $height;

        $newWidth = $this->width;
        $newHeight = $this->height;

        if ($sourceRatio !== $thumbRatio) {
            if ($this->width >= $this->height) {
                if ($thumbRatio > 1) {
                    $newHeight = $this->width / $thumbRatio;
                    if ($newHeight > $this->height) {
                        $newWidth = $this->height * $thumbRatio;
                        $newHeight = $this->height;
                    }
                } elseif ($thumbRatio == 1) {
                    $newWidth = $this->height;
                    $newHeight = $this->height;
                } else {
                    $newWidth = $this->height * $thumbRatio;
                }
            } else {
                if ($thumbRatio > 1) {
                    $newHeight = $this->width / $thumbRatio;
                } elseif ($thumbRatio == 1) {
                    $newWidth = $this->width;
                    $newHeight = $this->width;
                } else {
                    $newHeight = $this->width / $thumbRatio;
                    if ($newHeight > $this->height) {
                        $newHeight = $this->height;
                        $newWidth = $this->height * $thumbRatio;
                    }
                }
            }
        }

        $resizedImage = imagecreatetruecolor($width, $height);
        imagealphablending($resizedImage, false);

        imagecopyresampled(
            $resizedImage,
            $this->image,
            0,
            0,
            round(($this->width - $newWidth) / 2),
            round(($this->height - $newHeight) / 2),
            $width,
            $height,
            $newWidth,
            $newHeight
        );

        $this->image = $resizedImage;
    }

    public function save($file, $quality = 90)
    {
        switch ($this->mime) {
            case 'image/jpeg':
                return imagejpeg($this->image, $file, $quality);
                break;
            case 'image/png':
                imagesavealpha($this->image, true);
                return imagepng($this->image, $file);
                break;
            case 'image/gif':
                return imagegif($this->image, $file);
                break;
        }
        return false;
    }
}
