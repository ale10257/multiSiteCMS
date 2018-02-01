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

    /** @var Session */
    private $session;
    /** @var OrderRepository */
    private $repository;
    /** @var User */
    private $user;

    /**
     * OrderService constructor.
     * @param Session $session
     * @param User $user
     * @param OrderRepository $repository
     */
    public function __construct(Session $session, User $user, OrderRepository $repository)
    {
        $this->session = $session;
        $this->user = $user;
        $this->repository = $repository;
    }

    /**
     * @return void
     */
    private function createOrder()
    {
        $user = null;
        if (!$this->user->isGuest) {
            $this->repository->user_id = $this->user->identity->getId();
        } else {
            $user = UserReadRepository::findNoRegUser();
            $this->repository->user_id = $user->id;
        }

        if (!$this->repository->user_id) {
            throw new NotFoundException('User not found!');
        }

        $this->repository->status = $this->repository::STATUS_ORDER_CREATION;
        $this->repository->site_constant = SITE_ROOT_NAME;
        $this->repository->ip_address = $_SERVER['REMOTE_ADDR'];

        $this->repository->saveItem();
    }

    public function getOrderId()
    {
        if (!$this->session->has(self::SESSION_KEY)) {
            $this->createOrder();
            $this->session->set(self::SESSION_KEY, $this->repository->id);
        }

        return $this->session->get(self::SESSION_KEY);
    }

    /**
     * @return mixed
     */
    public function checkOrder()
    {
        return $this->session->get(OrderService::SESSION_KEY);
    }

    /**
     * @param int $id
     * @return OrderRepository
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOrder(int $id)
    {
        return $this->repository->getItem($id);
    }

    /**
     * @param int $id
     * @return OrderRepository
     */
    public function getOrderForAdmin(int $id)
    {
        $this->repository = $this->repository::find()->where(['id' => $id])->with('orderProducts')->with('user')->one();
        return $this->repository;
    }

    /**
     * @param int $id
     * @return OrderRepository
     */
    public function getOrderForSend(int $id)
    {
        return $this->repository::find()->where(['id' => $id])->with('orderProducts.product')->one();
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
     * @param OrderRepository $repository
     * @return bool
     */
    public function checkStatusOrderClosed(OrderRepository $repository)
    {
        return $repository === $repository::STATUS_ORDER_CLOSED;
    }

    /**
     * @param int $id
     * @throws Throwable
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->repository->deleteItem();
    }

    /**
     * @param $order_id
     * @param $status
     * @throws \yii\db\Exception
     */
    public function changeStatus($order_id, $status)
    {
        $this->repository::changeStatus($order_id, $status);
    }

}