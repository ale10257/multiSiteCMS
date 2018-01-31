<?php

namespace app\components\helpers\phpjquery;

use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii;
use app\core\workWithFiles\ImgThumb;

class PhpJqueryHelper
{
    /**
     * @param $text
     * @param $webDir
     * @return string
     * @throws yii\base\Exception
     */
    public static function changeImages($text, $webDir)
    {
        $dom = \phpQuery::newDocumentHTML($text);

        if ($images = $dom->find('img')) {

            $imgThumb = new ImgThumb();
            $imgThumb->web_dir = $webDir;
            $imgThumb->thumb_dir = 'thumb';
            $width = null;
            $height = null;

            foreach ($images as $image) {

                $style = explode(';', pq($image)->attr('style'));
                $styleFloat = null;

                foreach ($style as $item) {
                    $i = explode(':', $item);
                    if (!empty($i[0])) {
                        $i[0] = trim($i[0]);
                    }
                    if (!empty($i[1])) {
                        $i[1] = trim($i[1]);
                    }
                    if ($i[0] == 'width') {
                        $width = (int)$i[1];
                    }
                    if ($i[0] == 'height') {
                        $height = (int)$i[1];
                    }
                    if ($i[0] == 'float') {
                        if ($i[1] == 'left') {
                            $styleFloat = 'float: left; margin: 0px 10px 10px 0px;';
                        } elseif ($i[1] == 'right') {
                            $styleFloat = 'float: right; margin: 0px 0px 10px 10px;';
                        }
                    }
                }

                if ($width && $height) {
                    $imgThumb->width = $width;
                    $imgThumb->height = $height;
                    $file = basename(pq($image)->attr('src'));
                    if ($img = $imgThumb->checkFile($file)) {
                        if (!pq($image)->parent('a')->attr('href')) {
                            $a = Html::a('', pq($image)->attr('src'), ['data-fancybox' => 'gallery']);
                            pq($image)->wrap($a);
                            pq($image)->attr('src', $img->webThumbPath);
                            pq($image)->removeAttr('style');
                            pq($image)->removeAttr('width');
                            pq($image)->removeAttr('height');
                        }
                    }
                }

                if ($styleFloat) {
                    pq($image)->attr('style', $styleFloat);
                }

            }
        }

        return pq($dom)->html();
    }

    /**
     * @param $oldText
     * @param $text
     * @param $webDir
     */
    public static function deleteImagesFromFS($oldText, $text, $webDir)
    {
        $domOld = \phpQuery::newDocumentHTML($oldText);
        $domNew = \phpQuery::newDocumentHTML($text);
        $arrOld = [];
        $arrNew = [];

        if ($imagesOld = $domOld->find('img')) {
            foreach ($imagesOld as $imgOld) {
                $name = basename(pq($imgOld)->attr('src'));
                $arrOld[$name] = $name;
            }
        }

        if ($arrOld && ($imagesNew = $domNew->find('img'))) {
            foreach ($imagesNew as $imgNew) {
                $name = basename(pq($imgNew)->attr('src'));
                $arrNew[$name] = $name;
            }
            foreach ($arrNew as $img) {
                if (key_exists($img, $arrOld)) {
                    unset($arrOld[$img]);
                }
            }
        }

        if ($arrOld) {
            $dir = FileHelper::normalizePath(yii::getAlias('@webroot'));
            $dir = $dir . FileHelper::normalizePath($webDir);
            foreach ($arrOld as $img) {
                if ($images = FileHelper::findFiles($dir, ['only' => [$img]])) {
                    foreach ($images as $item) {
                        unlink($item);
                    }
                }
            }
        }
    }
}
