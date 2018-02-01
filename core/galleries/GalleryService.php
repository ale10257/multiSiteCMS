<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:15
 */

namespace app\core\galleries;

use app\core\galleries\forms\GalleryForm;
use app\core\galleries\forms\GalleryImageForm;
use app\core\galleries\repositories\GalleryImageRepository;
use app\core\galleries\repositories\GalleryRepository;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
use app\core\workWithFiles\helpers\RemoveDirectory;

class GalleryService
{
    /** @var GalleryRepository */
    private $galleryRepository;
    /** @var GalleryImageRepository */
    private $imageRepository;
    /** @var GalleryForm */
    private $galleryForm;
    /** @var GalleryImageForm */
    private $galleryImageForm;

    /**
     * GalleryService constructor.
     * @param GalleryRepository $galleryRepository
     * @param GalleryImageRepository $imageRepository
     * @param GalleryForm $galleryForm
     * @param GalleryImageForm $galleryImageForm
     */
    public function __construct(GalleryRepository $galleryRepository, GalleryImageRepository $imageRepository, GalleryForm $galleryForm, GalleryImageForm $galleryImageForm)
    {
        $this->galleryRepository = $galleryRepository;
        $this->imageRepository = $imageRepository;
        $this->galleryForm = $galleryForm;
        $this->galleryImageForm = $galleryImageForm;
    }

    public function index()
    {
        /** @var $root GalleryRepository */
        if (!$root = $this->galleryRepository->getRoot()) {
            $this->createRoot();
        }
    }

    /**
     * @param GalleryForm $form
     * @return int
     */
    public function create(GalleryForm $form)
    {
        $form->alias = Inflector::slug($form->name);

        /** @var $root GalleryRepository */
        $root = $this->galleryRepository->getRoot();
        $gallery = new GalleryRepository();
        $gallery->checkUniqAlias($form->alias, $root->tree);
        $gallery->insertValues($form);
        $gallery->prependTo($root);
        return $gallery->id;
    }

    /**
     * @param GalleryForm $form
     * @param int $id
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(GalleryForm $form, int $id)
    {
        $gallery = $this->galleryRepository->getItem($id);
        $web_dir = $gallery->getWebDir();

        $gallery->insertValues($form);
        $gallery->saveItem();

        if ($images = $form->uploadAnyFile($web_dir, 'any_images')) {
            $sort = $this->imageRepository->getNumLastElement(['galleries_id' => $gallery->id], 'sort');
            foreach ($images as $image) {
                $img = new GalleryImageRepository();
                $img->name = $image;
                $img->galleries_id = $gallery->id;
                $img->sort = $sort;
                $img->saveItem();
                $sort++;
            }
        }
    }

    public function getNewForm()
    {
        return $this->galleryForm;
    }

    /**
     * @param int $id
     * @return GalleryForm
     * @throws NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        if (!$gallery = $this->galleryRepository::find()
            ->where(['id' => $id])
            ->with([
                'images' => function ($q) {
                    /**@var \yii\db\ActiveQuery $q */
                    $q->orderBy(['sort' => SORT_ASC]);
                }
            ])
            ->one()) {
            throw new NotFoundHttpException();
        }

        $this->galleryForm->createUpdateForm($gallery);
        $this->galleryForm->webDir = $gallery->getWebDir();

        if ($gallery->images) {
            foreach ($gallery->images as $image) {
                $img = new GalleryImageForm();
                $img->createUpdateForm($image);
                $this->galleryForm->uploaded_images[] = $img;
            }
        }

        return $this->galleryForm;
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        $gallery = $this->galleryRepository->getItem($id);
        $gallery->deleteItem();
        RemoveDirectory::removeDirectory($gallery->getWebDir());
    }

    /**
     * @return GalleryRepository
     */
    private function createRoot()
    {
        $root = new GalleryRepository();
        $root->name = SITE_ROOT_NAME;
        $root->alias = SITE_ROOT_NAME;
        $root->tree = 1;
        $root->makeRoot();
        return $root;
    }
}