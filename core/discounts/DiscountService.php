<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:11
 */

namespace app\core\discounts;

use app\core\cache\CacheEntity;

class DiscountService
{
    /** @var DiscountRepository  */
    private $repository;
    /** @var DiscountForm  */
    private $form;
    /** @var CacheEntity */
    private $cache;

    public function __construct(DiscountRepository $repository, DiscountForm $form, CacheEntity $cache)
    {
        $this->repository = $repository;
        $this->form = $form;
        $this->cache = $cache;
    }

    /**
     * @param DiscountForm $form
     * @return int
     */
    public function create(DiscountForm $form)
    {
        $this->repository->insertValues($form);
        $this->repository->saveItem();
        $this->cache->deleteItem($this->cache::DISCOUNT);
        return $this->repository->id;
    }

    /**
     * @param DiscountForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(DiscountForm $form, int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->repository->insertValues($form);
        $this->repository->saveItem();
        $this->cache->deleteItem($this->cache::DISCOUNT);
    }

    /**
     * @return DiscountForm
     */
    public function getNewForm()
    {
        return $this->form;
    }

    /**
     * @param int $id
     * @return DiscountForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->form->createUpdateForm($this->repository);
        return $this->form;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function delete(int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->repository->deleteItem();
        $this->cache->deleteItem($this->cache::DISCOUNT);
    }
}