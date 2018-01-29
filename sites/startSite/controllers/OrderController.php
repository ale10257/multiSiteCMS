<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.01.18
 * Time: 7:39
 */

namespace app\sites\startSite\controllers;

use app\components\helpers\FirstErrors;
use app\core\cart\forms\OrderFormService;
use app\core\cart\forms\SendOrder;
use app\core\cart\OrderCheckService;
use app\core\cart\OrderProductService;
use app\core\categories\CacheCategory;
use app\sites\startSite\BaseController;
use Yii;
use yii\filters\VerbFilter;

class OrderController extends BaseController
{
    /** @var OrderProductService */
    private $_service;
    /** @var OrderFormService */
    private $_formService;
    /** @var SendOrder */
    private $_sendOrder;
    /** @var \yii\web\Session */
    private $_session;

    /**
     * OrderController constructor.
     * @param $id
     * @param $module
     * @param CacheCategory $cacheCategory
     * @param OrderCheckService $orderCheckService
     * @param OrderProductService $service
     * @param OrderFormService $formService
     * @param SendOrder $sendOrder
     */
    public function __construct(
        $id,
        $module,
        CacheCategory $cacheCategory,
        OrderCheckService $orderCheckService,
        OrderProductService $service,
        OrderFormService $formService,
        SendOrder $sendOrder
    )
    {
        $this->_service = $service;
        $this->_formService = $formService;
        $this->_sendOrder = $sendOrder;
        $this->_session = yii::$app->session;
        parent::__construct($id, $module, $cacheCategory, $orderCheckService);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'change-order' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $order_product = $this->_service->getProductsForCart();
        $cart_data = $this->orderCheckService->productsCount();

        return $this->render('cart', [
            'order_product' => $order_product,
            'formModel' => $this->_formService->getForm(),
            'cart_data' => $cart_data,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionChangeOrder(int $id)
    {
        $formModel = $this->_service->getNewForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->_session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->_service->update($formModel, $id);
                return $this->redirect(yii::$app->request->referrer);
            } catch (\Exception $e) {
                $this->_session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        return $this->goHome();
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSendOrder()
    {
        $formModel = $this->_formService->getForm();

        if ($formModel->load(yii::$app->request->post())) {
            if (!$formModel->validate()) {
                $this->_session->setFlash('error', FirstErrors::get($formModel));
                return $this->redirect(yii::$app->request->referrer);
            }
            try {
                $this->_sendOrder->sendEmail($formModel, yii::$app->params['adminEmail'], yii::$app->name);
                $this->_session->setFlash('success', 'Спасибо за заказ. Мы свяжемся с вами в ближайшее время.');
                return $this->goHome();
            } catch (\Exception $e) {
                $this->_session->setFlash('error', $e->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
        }

        throw new \DomainException();
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->_service->deleteOneProduct($id);
        return $this->redirect(yii::$app->request->referrer);
    }

}