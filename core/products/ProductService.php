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
    private $_repository;
    private $_imagesRepository;
    private $_imageForm;
    private $_productForm;
    private $_cacheCategory;

    /**
     * ProductService constructor.
     * @param CacheCategory $_cacheCategory
     */
    public function __construct(CacheCategory $_cacheCategory)
    {
        $this->_repository = new ProductRepository();
        $this->_imagesRepository = new ProductImagesRepository();
        $this->_imageForm = new ProductImageForm();
        $this->_productForm = new ProductForm();
        $this->_cacheCategory = $_cacheCategory;
    }

    /**
     * @param int|null $category_id
     * @return ProductForm
     */
    public function index(int $category_id = null)
    {
        if ($category_id === null) {
            return $this->_productForm;
        }
        $this->_productForm->categories_id = $category_id;
        return $this->_productForm;
    }

    /**
     * @param int|null $category_id
     * @return CategoryRepository
     * @throws NotFoundHttpException
     */
    public function getCategory(int $category_id = null)
    {
        if (!$category = $this->_repository::getCategoryForId($category_id)) {
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
        $this->_repository->insertValues($form, true);
        $this->_repository->saveItem();

        return $this->_repository->id;
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
        $product = $this->_repository->getItem($id);
        $webDir = $product->getWebDir();
        $oldCategory = $product->categories_id;
        $product->insertValues($form);
        $product->saveItem();

        if ($images = $form->uploadAnyFile($webDir, 'any_images')) {
            $sort = $this->_imagesRepository->getNumLastElement(['products_id' => $product->id], 'sort');
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
        $form = $this->_productForm;
        $form->categories_id = $category->id;
        $categories = $this->_cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
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
        if (!$product = $this->_repository::find()
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

        $this->_productForm->createUpdateForm($product);

        $categories = $this->_cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
        $this->_productForm->categories = ArrayHelper::map($categories, 'id', 'name');

        if ($product->images) {
            $this->_productForm->webDir = $product->getWebDir();
            foreach ($product->images as $image) {
                $imageForm = new ProductImageForm();
                $imageForm->createUpdateForm($image);
                $this->_productForm->uploaded_images[] = $imageForm;
            }
        }

        return $this->_productForm;
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
        $product = $this->_repository->getItem($id);
        $product->deleteItem();
        RemoveDirectory::removeDirectory($product->getWebDir());
    }

    /**
     * @return mixed
     */
    public function getLeavesCategories()
    {
        return $this->_cacheCategory->getLeavesCategory(CategoryRepository::RESERVED_TYPE_PRODUCT);
    }

    public function changeActive(int $id, int $status = null)
    {
        $this->_repository->changeActive($id, $status);
    }
}