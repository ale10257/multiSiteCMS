<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 11:11
 */

namespace app\core\cart;

use app\components\user\User;
use app\core\cart\repositories\OrderRepository;
use app\core\NotFoundException;
use Throwable;
use yii\web\Session;
use app\core\user\repositories\UserReadRepository;

class OrderService
{
    const SESSION_KEY = 'order_id';

    /**
     * @var Session
     */
    private $_session;
    /**
     * @var OrderRepository
     */
    private $_repository;
    /**
     * @var User
     */
    private $_user;

    /**
     * OrderService constructor.
     * @param Session $session
     * @param User $user
     */
    public function __construct(Session $session, User $user)
    {
        $this->_session = $session;
        $this->_user = $user;
        $this->_repository = new OrderRepository();
    }

    /**
     * @return void
     */
    private function createOrder()
    {
        $user = null;
        if (!$this->_user->isGuest) {
            $this->_repository->user_id = $this->_user->identity->getId();
        } else {
            $user = UserReadRepository::findNoRegUser();
            $this->_repository->user_id = $user->id;
        }

        if (!$this->_repository->user_id) {
            throw new NotFoundException('User not found!');
        }

        $this->_repository->status = $this->_repository::STATUS_ORDER_CREATION;
        $this->_repository->site_constant = SITE_ROOT_NAME;
        $this->_repository->ip_address = $_SERVER['REMOTE_ADDR'];

        $this->_repository->saveItem();
    }

    public function getOrderId()
    {
        if (!$this->_session->has(self::SESSION_KEY)) {
            $this->createOrder();
            $this->_session->set(self::SESSION_KEY, $this->_repository->id);
        }

        return $this->_session->get(self::SESSION_KEY);
    }

    /**
     * @return mixed
     */
    public function checkOrder()
    {
        return $this->_session->get(OrderService::SESSION_KEY);
    }

    /**
     * @param int $id
     * @return OrderRepository
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOrder(int $id)
    {
        return $this->_repository->getItem($id);
    }

    /**
     * @param int $id
     * @return OrderRepository
     */
    public function getOrderForAdmin(int $id)
    {
        $this->_repository = $this->_repository::find()->where(['id' => $id])->with('orderProducts')->with('user')->one();
        return $this->_repository;
    }

    /**
     * @param int $id
     * @return OrderRepository
     */
    public function getOrderForSend(int $id)
    {
        return $this->_repository::find()->where(['id' => $id])->with('orderProducts.product')->one();
    }

    /**
     * @param OrderRepository $repository
     * @param string $data
     */
    public function createData(OrderRepository $repository, string $data)
    {
        $repository->data = $data;
        $repository->saveItem();
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        $order = $this->_repository::find()->where(['id' => $id])->with('orderProducts')->one();
        if ($order->status != $this->_repository::STATUS_ORDER_CLOSED) {
            if ($orderService = new OrderProductService($this)) {
                foreach ($order->orderProducts as $orderProduct) {
                    $orderService->deleteOneProduct($orderProduct->id, $orderProduct);
                }
            }
        }

        $order->deleteItem();
    }

    /**
     * @param $order_id
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeStatus($order_id, $status)
    {
        $this->_repository::changeStatus($order_id, $status);
    }

}