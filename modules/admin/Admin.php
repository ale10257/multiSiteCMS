<?php

namespace app\modules\admin;

use yii;

/**
 * admin module definition class
 */
class Admin extends \yii\base\Module
{
    public $layout = 'main';

    public function init()
    {
        parent::init();
        yii::$app->errorHandler->errorAction='admin/default/error';
        /*$this->modules = [
            'filemanager' => [
                'class' => 'app\modules\fileManager\SimpleFilemanagerModule',
            ]
        ];*/
    }
}
