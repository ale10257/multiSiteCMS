<?php

namespace app\modules\filemanager\controllers;

use app\modules\filemanager\models\Directory;
use app\modules\filemanager\SimpleFilemanagerModule;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class FileManagerController
 * @package app\modules\filemanager\controllers
 * @property SimpleFilemanagerModule $module
 */
class FileManagerController extends Controller
{
    /**
     * @param null $path
     *
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionIndex($path = null)
    {
        if (strstr($path, '../')) {
            throw new BadRequestHttpException();
        }

        try {
            $directory = Directory::createByPath($path);
            $list = $directory->list;
        } catch (\Exception $e) {
            yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }

        return $this->render('index', [
            'directory' => $directory,
            'dataProvider' => new ArrayDataProvider(
                [
                    'allModels' => $list,
                    'sort' => [
                        'attributes' => ['name', 'time'],
                    ],
                ]
            )
        ]);
    }
}