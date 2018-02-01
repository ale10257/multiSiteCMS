<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 21:27
 */

namespace app\modules\admin\controllers\menuAdmin;

use app\components\helpers\FirstErrors;
use app\core\accessSites\AccessSearch;
use app\core\accessSites\AccessService;
use app\core\user\services\CheckCan;
use app\modules\admin\controllers\BaseAdminController;
use Yii;
use yii\filters\VerbFilter;

class SitesAdminController extends BaseAdminController
{


    /**
     * @var AccessSearch
     */
    private $search;
    /**
     * @var AccessService
     */
    private $service;

    /**
     * SitesAdminController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param AccessSearch $search
     * @param AccessService $service
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, AccessSearch $search, AccessService $service)
    {
        parent::__construct($id, $module, $checkCan);
        $this->search = $search;
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
                    'delete' => ['post'],
                ],
            ],
        ];
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
            'formModel' => $this->service->getNewForm()
        ]);
    }
    /**
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $formModel = $this->service->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $id = $this->service->create($formModel);
                return $this->redirect(['index', 'id' => $id]);
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
