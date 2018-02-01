<?php

namespace app\modules\feedback\controllers;

use app\components\helpers\FirstErrors;
use app\core\feedback\FeedbackService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `feedback` module
 */
class DefaultController extends Controller
{
    /** @var FeedbackService */
    private $service;

    /**
     * DefaultController constructor.
     * @param $id
     * @param $module
     * @param FeedbackService $service
     */
    public function __construct($id, $module, FeedbackService $service)
    {
        parent::__construct($id, $module);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $formModel = $this->service->getForm();
        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                yii::$app->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->service->sendFeedback($formModel, yii::$app->params['adminEmail'], yii::$app->name);
                yii::$app->session->setFlash('success', 'Спасибо за обращение!');
                return $this->goHome();
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        return $this->goHome();
    }
}
