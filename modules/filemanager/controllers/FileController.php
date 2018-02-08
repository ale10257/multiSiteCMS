<?php

namespace app\modules\filemanager\controllers;

use app\modules\filemanager\models\Directory;
use app\modules\filemanager\models\File;
use app\modules\filemanager\models\UploadForm;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;

class FileController extends Controller
{
    /**
     * @param $path
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionUpload($path)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }

        $directory = Directory::createByPath($path);

        $model = new UploadForm();
        $model->path = $path;

        if (\Yii::$app->request->isPost) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if ($model->upload()) {
                return $this->redirect(['default/index', 'path' => $model->path]);
            }
        }

        return $this->render('upload', [
            'directory' => $directory,
            'model' => $model
        ]);
    }

    /**
     * @param $path
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionDelete($path)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }

        $file = File::createByPath($path);

        unlink($file->fullPath);

        return $this->redirect(['default/index', 'path' => $file->directory->path]);

    }
}