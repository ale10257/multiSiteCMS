<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.01.18
 * Time: 11:27
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

class DeleteDomainController extends Controller
{
    /**
     * @throws \yii\base\ErrorException
     */
    public function actionIndex()
    {
        $site_constant = $this->prompt('Enter SITE CONSTANT', ['required' => true]);
        $arr = ['sites', 'config', 'web'];
        $app = FileHelper::normalizePath(yii::getAlias('@app'));
        foreach ($arr as $item) {
            $path = $app . DIRECTORY_SEPARATOR . $item . DIRECTORY_SEPARATOR . $site_constant;
            FileHelper::removeDirectory($path);
        }
    }
}