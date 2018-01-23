<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminSortableAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';

    public $css = [
        'css/image_sortable.css'
    ];
    public $js = [
        'js/sortable.js'
    ];
    public $depends = [
        'yii\jui\JuiAsset'
    ];
}
