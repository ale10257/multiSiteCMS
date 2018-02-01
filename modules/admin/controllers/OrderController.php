<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.01.18
 * Time: 8:21
 */

namespace app\modules\admin\controllers;

use app\components\helpers\FirstErrors;
use app\core\cart\OrderCheckService;
use app\core\cart\OrderProductService;
use app\core\cart\OrderService;
use app\core\user\services\CheckCan;
use app\core\cart\OrderSearch;
use yii\filters\VerbFilter;
use yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class OrderController extends BaseAdminController
{
    /** @var OrderService */
    private $service;
    /** @var OrderProductService */
    private $productService;
    /** @var OrderCheckService */
    private $checkService;

    /**
     * OrderController constructor.
     * @param string $id
     * @param $module
     * @param CheckCan $checkCan
     * @param OrderService $service
     * @param OrderProductService $productService
     * @param OrderCheckService $checkService
     * @throws ForbiddenHttpException
     * @throws yii\db\Exception
     */
    public function __construct(
        $id,
        $module,
        CheckCan $checkCan,
        OrderService $service,
        OrderProductService $productService,
        OrderCheckService $checkService
    ) {
        parent::__construct($id, $module, $checkCan);
        $this->service = $service;
        $this->productService = $productService;
        $this->checkService = $checkService;
        $this->checkService->checkTimeout();
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
                    'delete' => ['POST'],
                    'delete-item' => ['POST'],
                    'change-status' => ['POST'],
                    'change-num' => ['POST'],
                    'who-is' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);
        $this->checkService->setProductsCountForProvider($dataProvider);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $order_id
     * @param $status
     * @return yii\web\Response
     * @throws yii\db\Exception
     */
    public function actionChangeStatus($order_id, $status)
    {
        $this->service->changeStatus($order_id, $status);
        return $this->redirect(yii::$app->request->referrer . '#' . $order_id);
    }

    /**
     * @param $id
     * @return string
     * @throws yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $cart_data = $this->checkService->productsCount($id);
        $model = $this->productService->getOrderWithForms($id);

        return $this->render('view', ['cart_data' => $cart_data, 'model' => $model]);
    }

    /**
     * @param $id
     * @return yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->productService->deleteOrder($id);
        return $this->redirect(['index']);
    }

    /**
     * @param string $ip
     * @return array
     */
    public function actionWhoIs($ip)
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        $data = `whois $ip`;
        return [
            'data' => $data
        ];
    }

    /**
     * @param int $id
     * @return bool|Response
     * @throws yii\web\NotFoundHttpException
     */
    public function actionChangeNum(int $id)
    {
        $formModel = $this->productService->getUpdateForm($id);

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->productService->update($formModel, $id);
                return $this->redirect(yii::$app->request->referrer);
            } catch (\Exception $e) {
                yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return false;
    }

    /**
     * @param $id
     * @return Response
     * @throws \Throwable
     */
    public function actionDeleteItem($id)
    {
        try {
            $this->productService->deleteOneProduct($id);
            return $this->redirect(yii::$app->request->referrer);
        } catch (\Exception $e) {
            yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }

}
