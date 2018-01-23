<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 14.10.17
 * Time: 9:39
 */

namespace app\components\helpers\phpjquery;

use app\datasites\models\GalleryImage;
use app\datasites\models\GalleryModel;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use app\components\helpers\image\Image;
use app\modules\admin\models\SettingsGet;

class GalleryHelper
{
    public static function createGallery($gallery_id, $width, $height)
    {
        $gallery = GalleryModel::find()->where(['id' => $gallery_id])->with([
            'galleryImages' => function ($q) {
                /**
                 * @var $q ActiveQuery
                 */
                $q->orderBy(['sort' => SORT_ASC]);
            }
        ])->one();
        /**
         * @var $gallery GalleryModel
         */
        if ($gallery->galleryImages) {
            $web = '/upload/galleries/' . $gallery->alias . '/';
            $web_thumb = $web . 'thumb';
            $li = [];
            foreach ($gallery->galleryImages as $img) {
                /**
                 * @var $img GalleryImage
                 */
                if (Image::thumb($web . $img->name, $width, $height, $web_thumb, true, true)) {
                    $image = Html::img($web_thumb . '/' . $img->name, ['alt' => $img->alt]);
                    $a = Html::a($image, $web . $img->name, ['data-fancybox' => $gallery->alias]);
                    $li[] = Html::tag('li', $a);
                }
            }
            return $li;
        }
        return false;
    }
}
