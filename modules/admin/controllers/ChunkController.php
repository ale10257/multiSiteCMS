<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.12.17
 * Time: 6:07
 */

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\chunks\ChunkSearch;
use app\core\chunks\ChunkService;
use app\core\user\services\CheckCan;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;

class ChunkController extends BaseAdminController
{
    /**
     * @var ChunkService
     */
    private $_service;
    /**
     * @var ChunkSearch
     */
    private $_search;

    /**
     * ChunkController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param ChunkService $service
     * @param ChunkSearch $search
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, ChunkService $service, ChunkSearch $search)
    {
        parent::__construct($id, $module, $checkCan);
        $this->_service = $service;
        $this->_search = $search;
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
        $searchModel = $this->_search;
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'formModel' => $this->_service->getNewForm()
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $formModel = $this->_service->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            $formModel->alias = Inflector::slug($formModel->name);
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $id = $this->_service->create($formModel);
                return $this->redirect(['update', 'id' => $id]);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        throw new \DomainException('Unknown error.');
    }

    /**
     * @param int $id
     * @return \yii\web\Response|string
     */
    public function actionUpdate(int $id)
    {
        try {
            $formModel = $this->_service->getUpdateForm($id);
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
                $this->_service->update($formModel, $id);
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
            $this->_service->delete($id);
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }
}