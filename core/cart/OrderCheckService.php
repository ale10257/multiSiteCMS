<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05.01.18
 * Time: 9:52
 */

namespace app\core\cart;

use app\components\user\User;
use app\core\cart\repositories\OrderRepository;
use app\core\discounts\GetDiscount;
use yii\helpers\ArrayHelper;
use yii\web\Session;

class OrderCheckService
{
    /**
     * @var Session
     */
    private $_session;
    /**
     * @var User
     */
    private $_user;
    /**
     * @var OrderRepository
     */
    private $_repository;
    /**
     * @var GetDiscount
     */
    private $_discount;

    /**
     * OrderCheckService constructor.
     * @param Session $session
     * @param User $user
     * @param GetDiscount $discount
     */
    public function __construct(Session $session, User $user, GetDiscount $discount)
    {
        $this->_session = $session;
        $this->_user = $user;
        $this->_repository = new OrderRepository();
        $this->_discount = $discount;
    }

    public function checkOrderRegUser()
    {
        if (!$this->_session->has(OrderService::SESSION_KEY) && !$this->_user->isGuest) {
            $where = ['user_id' => $this->_user->id, 'status' => $this->_repository::STATUS_ORDER_CREATION];
            if ($this->_repository::find()->where($where)->count()) {
                $order = $this->_repository::find()->select('id')->where($where)->one();
                $this->_session->set(OrderService::SESSION_KEY, $order->id);
            }
        }
    }

    public function checkEmptyOrder()
    {
        if ($id = $this->_session->get(OrderService::SESSION_KEY)) {
            if (!$this->_repository::find()->where(['id' => $id])->count()) {
                $this->_session->remove(OrderService::SESSION_KEY);
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function checkTimeout()
    {
        $this->_repository::checkTimeout();
    }

    /**
     * @param int|null $order_id
     * @param OrderRepository|null $order
     * @return ProductCount
     */
    public function productsCount(int $order_id = null, OrderRepository $order = null)
    {
        $data = new ProductCount();

        if ($this->_session->has(OrderService::SESSION_KEY) && $order === null) {
            $order_id = $this->_session->get(OrderService::SESSION_KEY);
        }

        if ($order_id) {
            if ($order === null) {
                $order = $this->_repository::findOne($order_id);
            }
            if ($order) {
                $data->sum = $order->all_sum;
                $data->all_num = $order->all_total;
                $data->percent = $discount = $this->_discount->getDiscountPercent($data->sum);
                $data->discount = round(($data->sum / 100) * $data->percent, 0);
                $data->total = $data->sum - $data->discount;
                $data->product_ids = ArrayHelper::map($order->orderProducts, 'product_id', 'product_id');
            }
        }

        return $data;
    }

    /**
     * @param $provider
     */
    public function setProductsCountForProvider($provider) : void
    {
        /** @var \yii\data\ActiveDataProvider $provider */
        if (!$models = $provider->getModels()) {
            return;
        }
        foreach ($models as $model) {
            /** @var OrderRepository $model */
            $model->dataOrder = $this->productsCount($model->id, $model);
        }
    }
}