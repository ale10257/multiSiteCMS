<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:11
 */

namespace app\core\chunks;

use app\core\cache\CacheEntity;

class ChunkService
{
    /**
     * @var ChunkRepository
     */
    private $_repository;

    /**
     * @var ChunkForm
     */
    private $_form;
    /**
     * @var CacheEntity
     */
    private $_cache;

    /**
     * DiscountService constructor.
     * @param ChunkRepository $repository
     * @param ChunkForm $form
     */
    public function __construct(ChunkRepository $repository, ChunkForm $form, CacheEntity $cache)
    {
        $this->_repository = $repository;
        $this->_form = $form;
        $this->_cache = $cache;
    }

    /**
     * @param ChunkForm $form
     * @return int
     */
    public function create(ChunkForm $form)
    {
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();

        return $this->_repository->id;
    }

    /**
     * @param ChunkForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(ChunkForm $form, int $id)
    {
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();
        $this->deleteCache();
    }

    /**
     * @return ChunkForm
     */
    public function getNewForm()
    {
        return $this->_form;
    }

    /**
     * @param int $id
     * @return ChunkForm
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
        $this->deleteCache();
    }

    private function deleteCache()
    {
        if ($this->_cache->getItem($this->_repository->alias)) {
            $this->_cache->deleteItem($this->_repository->alias);
        }
    }
}