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
    /** @var ChunkRepository */
    private $repository;
    /** @var ChunkForm */
    private $form;
    /** @var CacheEntity */
    private $cache;

    /**
     * DiscountService constructor.
     * @param ChunkRepository $repository
     * @param ChunkForm $form
     */
    public function __construct(ChunkRepository $repository, ChunkForm $form, CacheEntity $cache)
    {
        $this->repository = $repository;
        $this->form = $form;
        $this->cache = $cache;
    }

    /**
     * @param ChunkForm $form
     * @return int
     */
    public function create(ChunkForm $form)
    {
        $this->repository->insertValues($form);
        $this->repository->saveItem();

        return $this->repository->id;
    }

    /**
     * @param ChunkForm $form
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(ChunkForm $form, int $id)
    {
        $this->repository = $this->repository->getItem($id);
        $this->repository->insertValues($form);
        $this->repository->saveItem();
        $this->deleteCache();
    }

    /**
     * @return ChunkForm
     */
    public function getNewForm()
    {
        return $this->form;
    }

    /**
     * @param int $id
     * @return ChunkForm
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
        $this->deleteCache();
    }

    private function deleteCache()
    {
        if ($this->cache->getItem($this->repository->alias)) {
            $this->cache->deleteItem($this->repository->alias);
        }
    }
}