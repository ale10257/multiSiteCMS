<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 5:58
 */

namespace app\core\products;

use app\core\base\BaseImageGallery;
use app\core\products\forms\ProductImageForm;
use app\core\products\repositories\ProductImagesRepository;

class ProductImageGallery extends BaseImageGallery
{
    /**
     * ProductImageGallery constructor.
     * @param ProductImagesRepository $repository
     */
    public function __construct(ProductImagesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $id
     * @return ProductImageForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getForm(int $id)
    {
        $repository = $this->repository->getItem($id);
        $form = new ProductImageForm();
        $form->createUpdateForm($repository);
        return $form;
    }

    /**
     * @param $sort
     */
    public function sortImage($sort) {
        $this->repository->changeSort(json_decode($sort), 'sort', 'products_id');
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function deleteImage(int $id) {
        parent::deleteImage($id);
        $this->repository->deleteSortItem('sort', $this->repository->sort, 'products_id', $this->repository->products_id);
    }
}