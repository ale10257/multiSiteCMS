<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.17
 * Time: 6:07
 */

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;

use app\core\user\services\CheckCan;
use app\core\userReg\UserRegSearch;
use app\core\userReg\UserRegService;
use Yii;
use yii\filters\VerbFilter;

class RegUserController extends BaseAdminController
{
    /**
     * @var UserRegService
     */
    private $service;
    /**
     * @var UserRegSearch
     */
    private $search;

    /**
     * RegUserController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param UserRegService $service
     * @param UserRegSearch $search
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, UserRegService $service, UserRegSearch $search)
    {
        parent::__construct($id, $module, $checkCan);
        $this->service = $service;
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $arr = parent::behaviors();
        $arr['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];
        return $arr;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = $this->search;
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
    public function actionCreate()
    {
        $formModel = $this->service->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->service->create($formModel);
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

       return $this->render('create', ['formModel' => $formModel]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response|string
     */
    public function actionUpdate(int $id)
    {
        try {
            $formModel = $this->service->getUpdateForm($id);
        } catch (\Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->service->update($formModel, $id);
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->render('update', ['formModel' => $formModel]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->service->delete($id);
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }
}