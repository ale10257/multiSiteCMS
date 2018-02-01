<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 5:46
 */

namespace app\core\base;

use app\core\workWithFiles\helpers\DeleteImages;

abstract class BaseImageGallery
{
    /** @var \app\core\products\repositories\ProductImagesRepository|\app\core\articles\repositories\ArticleImagesRepository */
    protected $repository;

    /**
     * @param $form
     * @param $id
     * @return void
     * @throws \yii\web\NotFoundHttpException
     */
    public function updateImage($form, $id) {
        $this->repository = $this->repository->getItem($id);
        $this->repository->insertValues($form);
        $this->repository->saveItem();
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteImage(int $id) {
        $this->repository = $this->repository->getItem($id);
        $dir = $this->repository->getWebDir();
        $this->repository->deleteItem();
        DeleteImages::deleteImages($dir, $this->repository->name);
    }

    /**
     * @param $sort
     * @throws \yii\db\Exception
     */
    public function sortImage($sort) {
        $this->repository->changeSort(json_decode($sort), 'sort');
    }
}