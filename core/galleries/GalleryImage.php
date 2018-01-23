<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 13:16
 */

namespace app\core\galleries;

use app\core\base\BaseImageGallery;
use app\core\galleries\forms\GalleryForm;
use app\core\galleries\forms\GalleryImageForm;
use app\core\galleries\repositories\GalleryImageRepository;

class GalleryImage extends BaseImageGallery
{
    /**
     * GalleryImage constructor.
     */
    public function __construct()
    {
        $this->_repository = new GalleryImageRepository();
    }

    /**
     * @param int $id
     * @return GalleryImageForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getForm(int $id)
    {
        $repository = $this->_repository->getItem($id);
        $form = new GalleryImageForm();
        $form->createUpdateForm($repository);
        return $form;
    }

    /**
     * @param $form
     * @param $id
     * @return GalleryImageForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function updateImage($form, $id)
    {
        parent::updateImage($form, $id);
        $form = new GalleryImageForm();
        $form->createUpdateForm($this->_repository);
        return $form;
    }
}