<?php

namespace app\sites\startSite\controllers;

use app\core\categories\CacheCategory;
use app\sites\startSite\BaseController;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\core\feedback\FeedbackService;

class SiteController extends BaseController
{
    /**
     * @var FeedbackService
     */
    private $_feedbackService;

    /**
     * SiteController constructor.
     * @param string $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param FeedbackService $feedbackService
     */
    public function __construct(string $id, $module, CacheCategory $cacheCategory, FeedbackService $feedbackService)
    {
        $this->_feedbackService = $feedbackService;
        parent::__construct($id, $module, $cacheCategory);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @return string
     */
    public function actionContact()
    {
        $form = $this->renderPartial('@app/modules/feedback/views/default/index',
            [
                'formModel' => $this->_feedbackService->getForm(),
                'file' => true,
            ]);
        return $this->render('contact', ['form' => $form]);
    }
}
