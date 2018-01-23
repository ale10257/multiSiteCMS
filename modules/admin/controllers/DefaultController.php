<?php

namespace app\modules\admin\controllers;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends BaseAdminController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
