<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 14:25
 */

namespace app\modules\admin\assets;


use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/';

    public $css = [
        'css/admin.css'
    ];
    public $js = [
        'js/admin.js'
    ];
}
