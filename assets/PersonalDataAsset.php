<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.11.17
 * Time: 7:38
 */

namespace app\assets;


use yii\web\AssetBundle;

class PersonalDataAsset extends AssetBundle
{
    public $sourcePath = '@app/static';

    public $css = [
        'css/person_data.css'
    ];

    public $js = [
        'js/personal_data.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
