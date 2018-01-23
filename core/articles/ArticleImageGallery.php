<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 11:23
 */

namespace app\core\articles;

use app\core\articles\forms\ArticleImageForm;
use app\core\articles\repositories\ArticleImagesRepository;
use app\core\base\BaseImageGallery;

class ArticleImageGallery extends BaseImageGallery
{
    /**
     * ArticleImageGallery constructor.
     */
    public function __construct()
    {
        $this->_repository = new ArticleImagesRepository();
    }

    /**
     * @param int $id
     * @return ArticleImageForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getForm(int $id)
    {
        $repository = $this->_repository->getItem($id);
        $form = new ArticleImageForm();
        $form->createUpdateForm($repository);
        return $form;
    }
}