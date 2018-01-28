<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.01.18
 * Time: 5:26
 */

namespace app\core\workWithFiles\helpers;

use app\core\articles\repositories\ArticleRepository;
use app\core\products\repositories\ProductRepository;
use Yii;
use yii\helpers\FileHelper;

class ChangeDirectory
{
    /**
     * @param string $oldDir
     * @param ArticleRepository|ProductRepository $object
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public static function changeDirectory(string $oldDir, $object)
    {
        $object = $object->getItem($object->id);
        $oldDir = FileHelper::normalizePath(yii::getAlias('@webroot') . $oldDir);
        $newDir = FileHelper::normalizePath(yii::getAlias('@webroot') . $object->getWebDir());
        if (is_dir($newDir)) {
            FileHelper::removeDirectory($newDir);
        }
        if (!is_dir($oldDir)) {
            FileHelper::createDirectory($newDir);
            return;
        }
        FileHelper::copyDirectory($oldDir, $newDir);
        FileHelper::removeDirectory($oldDir);
    }
}