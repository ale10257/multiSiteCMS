<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.10.17
 * Time: 20:49
 */

namespace app\components\helpers;

use yii\helpers\Html;
use yii\helpers\Url;


class RemoveImgAdminHelper
{
    /**
     * @param int $id
     * @param string $image
     * @param string $controller
     * @param int $width
     * @return string|bool
     */
    public static function addElementRemove(int $id, string $image, string $controller, int $width, string $action = null, bool $data_method = true)
    {
            if ($action === null) {
                $action = 'delete-image';
            }

            $fa = Html::tag('i', '', ['class' => 'fa fa-times']);

            $options = ['data-fancybox' => 'img'];

            $img = Html::img($image, ['style' => 'width: ' . $width . 'px; height: auto;']);

            $options_fa = $data_method ? ['class' => 'del-img', 'data-method' => 'post'] : ['class' => 'del-img'];

            $fa_link = Html::a($fa, Url::to(['/admin/' . $controller . '/' . $action, 'id' => $id]), $options_fa);

            $a = Html::a($img, $image, $options);

            $result = Html::tag('div', $a . $fa_link, ['class' => 'img-wrap-del-img']);

            return $result;
    }
}
