<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\modules\admin\models\Redactor;
use yii;

class RedactorController extends Controller
{
    /**
     * @param $dir
     * @return array
     * @throws yii\base\Exception
     * @throws yii\web\BadRequestHttpException
     */
    public function actionUpload($dir)
    {
        if (yii::$app->request->isPost) {
            yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            $model = new Redactor();
            $model->file = yii\web\UploadedFile::getInstanceByName('file');
            $dir_save = yii\helpers\FileHelper::normalizePath(yii::getAlias('@webroot'));
            $dir_save = $dir_save . $dir;
            if (!is_dir($dir_save)) {
                yii\helpers\FileHelper::createDirectory($dir_save);
            }
            $name = uniqid() . '.' . $model->file->extension;
            if ($model->validate('file')) {
                if ($model->file->saveAs($dir_save . $name)) {
                    return [
                        'filelink' => $dir . $name,
                    ];
                }
            }
            throw new \DomainException('Not validate file!');
        }
        throw new yii\web\BadRequestHttpException();
    }
}
