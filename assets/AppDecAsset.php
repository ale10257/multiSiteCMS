<?php

namespace app\assets;


use yii\web\AssetBundle;

class AppDecAsset  extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/media.css',
    ];
    public $js = [
        'js/js.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
        'app\assets\PersonalDataAsset',
    ];
}
