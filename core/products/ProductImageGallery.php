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
}