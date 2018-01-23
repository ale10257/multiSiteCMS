<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.01.18
 * Time: 14:18
 */

namespace app\core\workWithFiles\helpers;

use Yii;
use yii\helpers\FileHelper;

class RemoveDirectory
{
    /**
     * @param $webDir
     * @throws \yii\base\ErrorException
     */
    public static function removeDirectory($webDir)
    {
        $dir = FileHelper::normalizePath(yii::getAlias('@webroot')) . $webDir;
        if (is_dir($dir)) {
            FileHelper::removeDirectory($dir);
        }
    }
}