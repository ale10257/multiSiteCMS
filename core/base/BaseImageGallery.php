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
    /**
     * @var \app\core\products\repositories\ProductImagesRepository|\app\core\articles\repositories\ArticleImagesRepository
     */
    protected $_repository;

    /**
     * @param $form
     * @param $id
     * @return void
     * @throws \yii\web\NotFoundHttpException
     */
    public function updateImage($form, $id) {
        $this->_repository = $this->_repository->getItem($id);
        $this->_repository->insertValues($form);
        $this->_repository->saveItem();
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteImage(int $id) {
        $this->_repository = $this->_repository->getItem($id);
        $dir = $this->_repository->getWebDir();
        $this->_repository->deleteItem();
        DeleteImages::deleteImages($dir, $this->_repository->name);
    }

    /**
     * @param $sort
     * @throws \yii\db\Exception
     */
    public function sortImage($sort) {
        $this->_repository->changeSort(json_decode($sort), 'sort');
    }
}