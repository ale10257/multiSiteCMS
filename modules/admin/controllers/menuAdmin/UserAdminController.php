<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 21:27
 */

namespace app\modules\admin\controllers\menuAdmin;

use app\components\helpers\FirstErrors;
use app\core\user\services\CheckCan;
use app\core\user\services\UserAdminService;
use app\modules\admin\controllers\BaseAdminController;
use yii\db\Exception;
use yii\filters\VerbFilter;
use app\core\user\services\SearchUserModel;

class UserAdminController extends BaseAdminController
{
    private $_adminService;

    /**
     * UserAdminController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @throws \yii\web\ForbiddenHttpException
     */
    public function __construct(string $id, $module, CheckCan $checkCan, UserAdminService $adminService)
    {
        $this->_adminService = $adminService;
        parent::__construct($id, $module, $checkCan);
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

    public function actionIndex()
    {
        $searchModel = new SearchUserModel();
        $dataProvider = $searchModel->search(\yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);

    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $formModel = $this->_adminService->getAdminForm();

        if ($formModel->load(\yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(\yii::$app->request->referrer);
            }

            try {
                $this->_adminService->createAdmin($formModel);
                $this->session->setFlash('success', 'Пользователь создан успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $this->session->setFlash('error', $e->getMessage());
                return $this->redirect(\yii::$app->request->referrer);
            }
        }

        return $this->render('create', ['formModel' => $formModel]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        try {
            $formModel = $this->_adminService->getAdminForm($id);
        } catch (\Exception $e) {
            \yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(\yii::$app->request->referrer);
        }

        if ($formModel->load(\yii::$app->request->post())) {

            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(\yii::$app->request->referrer);
            }
            try {
                $this->_adminService->updateAdmin($formModel, $id);
                $this->session->setFlash('success', 'Данные обновлены успешно!');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                \yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(\yii::$app->request->referrer);
            }
        }

        return $this->render('update',  ['formModel' => $formModel]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        try {
            $this->_adminService->delete($id);
        } catch (\Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

}
