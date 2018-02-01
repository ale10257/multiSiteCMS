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
use yii\web\Session;

class OrderCheckService
{
    /** @var Session */
    private $session;
    /** @var User */
    private $user;
    /** @var OrderRepository */
    private $repository;
    /** @var GetDiscount */
    private $discount;


    /**
     * OrderCheckService constructor.
     * @param Session $session
     * @param User $user
     * @param GetDiscount $discount
     * @param OrderRepository $repository
     */
    public function __construct(Session $session, User $user, GetDiscount $discount, OrderRepository $repository)
    {
        $this->session = $session;
        $this->user = $user;
        $this->repository = $repository;
        $this->discount = $discount;
    }

    public function checkOrderRegUser() : void
    {
        if (!$this->session->has(OrderService::SESSION_KEY) && !$this->user->isGuest) {
            $where = ['user_id' => $this->user->id, 'status' => $this->repository::STATUS_ORDER_CREATION];
            if ($this->repository::find()->where($where)->count()) {
                $order = $this->repository::find()->select('id')->where($where)->one();
                $this->session->set(OrderService::SESSION_KEY, $order->id);
            }
        }
    }

    public function checkEmptyOrder() : void
    {
        if ($id = $this->session->get(OrderService::SESSION_KEY)) {
            if (!$this->repository::find()->where(['id' => $id])->count()) {
                $this->session->remove(OrderService::SESSION_KEY);
            }
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function checkTimeout()
    {
        $this->repository::checkTimeout();
    }

    /**
     * @param int|null $order_id
     * @param OrderRepository|null $order
     * @return ProductCount
     */
    public function productsCount(int $order_id = null, OrderRepository $order = null)
    {
        $data = new ProductCount();

        if ($this->session->has(OrderService::SESSION_KEY) && $order === null) {
            $order_id = $this->session->get(OrderService::SESSION_KEY);
        }

        if ($order_id) {
            if ($order === null) {
                $order = $this->repository::findOne($order_id);
            }
            if ($order) {
                $data->sum = $order->all_sum;
                $data->all_num = $order->all_total;
                $data->percent = $discount = $this->discount->getDiscountPercent($data->sum);
                $data->discount = round(($data->sum / 100) * $data->percent, 0);
                $data->total = $data->sum - $data->discount;
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