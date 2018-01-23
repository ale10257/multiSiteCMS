<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.12.17
 * Time: 16:07
 */

namespace app\assets;

use yii\web\AssetBundle;

class FancyBoxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox';

    public $css = [
        'dist/jquery.fancybox.min.css'
    ];

    public $js = [
        'dist/jquery.fancybox.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}