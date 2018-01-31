<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.01.18
 * Time: 8:28
 */

namespace app\core\cart;

use app\core\cart\forms\OrderProductForm;
use app\core\cart\repositories\OrderProductRepository;
use app\core\NotFoundException;
use app\core\products\repositories\ProductRepository;

class OrderProductService
{
    /** @var OrderService */
    private $_orderService;
    /** @var OrderProductRepository */
    private $_productOrderRepository;
    /** @var OrderProductForm */
    private $_form;

    /**
     * OrderProductService constructor.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->_productOrderRepository = new OrderProductRepository();
        $this->_form = new OrderProductForm();
        $this->_orderService = $orderService;
    }

    /**
     * @param OrderProductForm $form
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function create(OrderProductForm $form)
    {
        if (!$form->product_id || !$form->image) {
            throw new NotFoundException();
        }

        if (!$form->order_id) {
            $form->order_id = $this->_orderService->getOrderId();
        }
        $product = ProductRepository::findOne($form->product_id);
        $this->checkCount($form->count, $product->count);
        $this->setCount($form, $product);
        $this->_productOrderRepository->insertValues($form);
        $this->_productOrderRepository->saveItem();
        $this->setAll($form->order_id, $form->count, $product->price);
        return $product->count;
    }

    /**
     * @param OrderProductForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(OrderProductForm $form, int $id)
    {
        $this->_productOrderRepository = $this->_productOrderRepository->getItem($id);
        $product = new ProductRepository();
        $product = $product->getItem($this->_productOrderRepository->product_id);
        $this->checkCount($form->count, $product->count + $this->_productOrderRepository->count);
        $count = $form->count - $this->_productOrderRepository->count;
        $product->count += $this->_productOrderRepository->count;
        $this->setCount($form, $product);
        $this->_productOrderRepository->count = $form->count;
        $this->_productOrderRepository->saveItem();

        $this->setAll($this->_productOrderRepository->order_id, $count, $product->price);
    }

    /**
     * @return OrderProductForm
     */
    public function getNewForm()
    {
        return $this->_form;
    }

    /**
     * @param int $id
     * @param OrderProductRepository|null $productRepository
     * @return OrderProductForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id, OrderProductRepository $productRepository = null)
    {
        if ($productRepository === null) {
            $productRepository = $this->_productOrderRepository->getItem($id);
        }
        $form = new OrderProductForm();
        $form->createUpdateForm($productRepository);
        return $form;
    }

    /**
     * @param int $id
     * @return repositories\OrderRepository
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOrderWithForms(int $id)
    {
        $order = $this->_orderService->getOrderForAdmin($id);
        if ($order->orderProducts) {
            foreach ($order->orderProducts as $orderProduct) {
                $orderProduct->form = $this->getUpdateForm($orderProduct->id, $orderProduct);
            }
        }

        return $order;
    }

    /**
     * @return OrderProductRepository[]|bool
     */
    public function getProductsForCart()
    {
        if (!$order_id = $this->_orderService->checkOrder()) {
            return false;
        }

        $products = $this->_productOrderRepository::find()->where(['order_id' => $order_id])->with('product.category')->all();
        if (!$products) {
            return false;
        }

        if ($products) {
            foreach ($products as $product) {
                $product->form = $this->getNewForm();
            }
        }

        return $products;
    }

    /**
     * @param int $id
     * @return OrderProductRepository|array|null|\yii\db\ActiveRecord
     */
    public function getOneProductForCart(int $id)
    {
        $product = $this->_productOrderRepository::find()->where(['id' => $id])->with('product.category')->one();
        $product->form = $this->getNewForm();
        return $product;
    }

    /**
     * @param int $id
     * @param OrderProductRepository|null $orderProductRepository
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteOneProduct(int $id, OrderProductRepository $orderProductRepository = null)
    {
        if ($orderProductRepository === null) {
            $orderProductRepository = $this->_productOrderRepository->getItem($id);
        }

        if (!$product = ProductRepository::findOne($orderProductRepository->product_id)) {
            throw new NotFoundException('Product not found!');
        }

        $product->count += $orderProductRepository->count;
        $product->saveItem();

        $orderProductRepository->deleteItem();

        if (!$orderProductRepository::find()->where(['order_id' => $orderProductRepository->order_id])->count()) {
            $this->_orderService->deleteOnly($orderProductRepository->order_id);
            return;
        }

        $count = $orderProductRepository->count * -1;
        $this->setAll($orderProductRepository->order_id, $count, $product->price);
    }


    /**
     * @param int $product_id
     * @return bool|int
     */
    public function checkOrderedProduct(int $product_id)
    {
        if ($order_id = $this->_orderService->checkOrder()) {
            return $this->_productOrderRepository::find()->where([
                'order_id' => $order_id,
                'product_id' => $product_id
            ])->count();
        }
        return false;
    }

    /**
     * @param OrderProductForm $form
     * @param ProductRepository $product
     * @return void
     */
    private function setCount(OrderProductForm $form, ProductRepository $product)
    {
        $product->count -= $form->count;
        $product->saveItem();
    }

    /**
     * @param int $count
     * @param int $productCount
     */
    private function checkCount(int $count, int $productCount)
    {
        if ($count > $productCount) {
            throw new \DomainException('Нельзя заказать больше, чем ' . $productCount . ' шт.');
        }
    }

    /**
     * @param int $repository_id
     * @param int $num
     * @param int $price
     * @throws \yii\web\NotFoundHttpException
     */
    private function setAll(int $repository_id, int $num, int $price)
    {
        $orderRepository = $this->_orderService->getOrder($repository_id);
        $orderRepository->all_sum = $orderRepository->all_sum + ($num * $price);
        $orderRepository->all_total += $num;
        $orderRepository->saveItem();
    }

}