<?php

namespace app\modules\filemanager\controllers;

use Yii;
use app\modules\filemanager\models\Directory;
use app\modules\filemanager\models\DirectoryForm;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class DirectoryController extends Controller
{
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'create' => ['post'],
                    'update' => ['post'],
                ],
            ]
        ];
    }

    /**
     * @param null|string $path
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionCreate($path = null)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }

        $model = new DirectoryForm();
        $model->path = $path;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
                return $this->redirect(['default/index', 'path' => $model->path]);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'directory' => Directory::createByPath($path)
        ]);
    }

    /**
     * @param string $path
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionUpdate($path)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }

        $directory = Directory::createByPath($path);

        $model = new DirectoryForm();
        $model->path = $directory->parent->path;
        $model->name = $directory->name;
        $model->isNew = false;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
                return $this->redirect(['default/index', 'path' => $model->path]);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'directory' => $directory
        ]);
    }

    /**
     * @param $path
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionDelete($path)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }
        $directory = Directory::createByPath($path);
        FileHelper::removeDirectory($directory->fullPath);

        return $this->redirect(['default/index', 'path' => $directory->parent->path]);
    }
}