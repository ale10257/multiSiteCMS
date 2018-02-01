<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.12.17
 * Time: 9:15
 */

namespace app\core\products;

use app\core\categories\CacheCategory;
use app\core\categories\CategoryRepository;
use app\core\interfaces\Service;
use app\core\products\forms\ProductForm;
use app\core\products\forms\ProductImageForm;
use app\core\products\repositories\ProductImagesRepository;
use app\core\products\repositories\ProductRepository;
use app\core\workWithFiles\helpers\ChangeDirectory;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
use app\core\workWithFiles\helpers\RemoveDirectory;

class ProductService implements Service
{
    /** @var ProductRepository  */
    private $repository;
    /** @var ProductImagesRepository  */
    private $imagesRepository;
    /** @var ProductImageForm  */
    private $imageForm;
    /** @var ProductForm  */
    private $productForm;
    /** @var CacheCategory  */
    private $cacheCategory;


    /**
     * ProductService constructor.
     * @param CacheCategory $cacheCategory
     * @param ProductRepository $repository
     * @param ProductImagesRepository $imagesRepository
     * @param ProductImageForm $imageForm
     * @param ProductForm $productForm
     */
    public function __construct(
        CacheCategory $cacheCategory,
        ProductRepository $repository,
        ProductImagesRepository $imagesRepository,
        ProductImageForm $imageForm,
        ProductForm $productForm
    )
    {
        $this->cacheCategory = $cacheCategory;
        $this->repository = $repository;
        $this->imagesRepository = $imagesRepository;
        $this->imageForm = $imageForm;
        $this->productForm = $productForm;
    }

    /**
     * @param int|null $category_id
     * @return ProductForm
     */
    public function index(int $category_id = null)
    {
        if ($category_id === null) {
            return $this->productForm;
        }
        $this->productForm->categories_id = $category_id;
        return $this->productForm;
    }

    /**
     * @param int|null $category_id
     * @return CategoryRepository
     * @throws NotFoundHttpException
     */
    public function getCategory(int $category_id = null)
    {
        if (!$category = $this->repository::getCategoryForId($category_id)) {
            throw new NotFoundHttpException('Category with id = ' . $category_id . ' not found');
        }
        return $category;
    }

    /**
     * @param ProductForm $form
     * @return int
     */
    public function create($form)
    {
        $form->alias = Inflector::slug($form->name);
        $this->repository->insertValues($form, true);
        $this->repository->saveItem();

        return $this->repository->id;
    }

    /**
     * @param ProductForm $form
     * @param int $id
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function update($form, int $id)
    {
        $product = $this->repository->getItem($id);
        $webDir = $product->getWebDir();
        $oldCategory = $product->categories_id;
        $product->insertValues($form);
        $product->saveItem();

        if ($images = $form->uploadAnyFile($webDir, 'any_images')) {
            $sort = $this->imagesRepository->getNumLastElement(['products_id' => $product->id], 'sort');
            foreach ($images as $image) {
                $img = new ProductImagesRepository();
                $img->name = $image;
                $img->products_id = $product->id;
                $img->sort = $sort;
                $img->saveItem();
                $sort++;
            }
        }

        $newCategory = $product->categories_id;

        if ($oldCategory != $newCategory) {
            ChangeDirectory::changeDirectory($webDir, $product);
        }
    }

    /**
     * @param int $category_id
     * @return ProductForm
     * @throws NotFoundHttpException
     */
    public function getNewForm(int $category_id)
    {
        $category = $this->getCategory($category_id);
        $form = $this->productForm;
        $form->categories_id = $category->id;
        $categories = $this->cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
        $form->categories = ArrayHelper::map($categories, 'id', 'name');

        return $form;
    }

    /**
     * @param int $id
     * @return ProductForm
     * @throws NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        if (!$product = $this->repository::find()
            ->where(['id' => $id])
            ->with('category')
            ->with([
                'images' => function ($q) {
                    /**@var \yii\db\ActiveQuery $q */
                    $q->orderBy(['sort' => SORT_ASC]);
                }
            ])
            ->one()) {
            throw new NotFoundHttpException();
        }

        $this->productForm->createUpdateForm($product);

        $categories = $this->cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
        $this->productForm->categories = ArrayHelper::map($categories, 'id', 'name');

        if ($product->images) {
            $this->productForm->webDir = $product->getWebDir();
            foreach ($product->images as $image) {
                $imageForm = new ProductImageForm();
                $imageForm->createUpdateForm($image);
                $this->productForm->uploaded_images[] = $imageForm;
            }
        }

        return $this->productForm;
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
        $product = $this->repository->getItem($id);
        $product->deleteItem();
        RemoveDirectory::removeDirectory($product->getWebDir());
    }

    /**
     * @return mixed
     */
    public function getLeavesCategories()
    {
        return $this->cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
    }

    /**
     * @param int $id
     * @param int|null $status
     */
    public function changeActive(int $id, int $status = null)
    {
        $this->repository->changeActive($id, $status);
    }

    /**
     * @param $jsonData
     * @throws \yii\db\Exception
     */
    public function sort($jsonData)
    {
        $this->repository->changeSort(json_decode($jsonData), 'sort');
    }
}