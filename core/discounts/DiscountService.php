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
    private $_repository;
    /** @var DiscountForm  */
    private $_form;
    /** @var CacheEntity */
    private $_cache;

    public function __construct(DiscountRepository $repository, DiscountForm $form, CacheEntity $cache)
    {
        $this->_repository = $repository;
        $this->_form = $form;
        $this->_cache = $cache;
    }

    /**
     * @param DiscountForm $form
     * @return int
     */
    public function create(DiscountForm $form)
    {
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();
        $this->_cache->deleteItem($this->_cache::DISCOUNT);
        return $this->_repository->id;
    }

    /**
     * @param DiscountForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(DiscountForm $form, int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();
        $this->_cache->deleteItem($this->_cache::DISCOUNT);
    }

    /**
     * @return DiscountForm
     */
    public function getNewForm()
    {
        return $this->_form;
    }

    /**
     * @param int $id
     * @return DiscountForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_form->createUpdateForm($this->_repository);
        return $this->_form;
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
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->deleteItem();
        $this->_cache->deleteItem($this->_cache::DISCOUNT);
    }
}