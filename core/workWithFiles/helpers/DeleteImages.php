<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.12.17
 * Time: 7:56
 */

namespace app\core\workWithFiles\helpers;

use Yii;
use yii\helpers\FileHelper;

class DeleteImages
{
    public static function deleteImages(string $dir, string $image)
    {
        $webRoot = FileHelper::normalizePath(yii::getAlias('@webroot'));

        if ($images = FileHelper::findFiles($webRoot . $dir, ['only' => [$image]])) {
            foreach ($images as $img) {
                unlink($img);
            }
        }
    }
}