<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.01.18
 * Time: 14:16
 */

namespace app\core\products\getProducts;

use app\core\categories\CacheCategory;
use app\core\categories\CategoryRepository;
use app\core\products\repositories\ProductRepository;
use app\core\settings\ThumbSettingImg;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GetProduct
{
    /** @var CacheCategory */
    private $_cacheCategory;
    /** @var ThumbSettingImg */
    private $_thumbSetting;

    /**
     * GetProduct constructor.
     * @param CacheCategory $cacheCategory
     * @param ThumbSettingImg $thumbSetting
     */
    public function __construct(CacheCategory $cacheCategory, ThumbSettingImg $thumbSetting)
    {
        $this->_cacheCategory = $cacheCategory;
        $this->_thumbSetting = $thumbSetting;
    }

    /**
     * @param $alias
     * @return DataCategory
     * @throws NotFoundHttpException
     * @throws \ImagickException
     * @throws \yii\base\Exception
     */
    public function getCategory($alias)
    {
        $categories = $this->_cacheCategory->getLeavesCategoryActive('product');

        $categories = ArrayHelper::index($categories, 'alias');

        if (empty($categories[$alias])) {
            throw new NotFoundHttpException();
        }
        /** @var CategoryRepository $category */
        $category = $categories[$alias];

        if (!ProductRepository::find()->where(['categories_id' => $category->id])->count()) {
            throw new NotFoundHttpException();
        }

        $products = ProductRepository::find()
            ->where(['categories_id' => $category->id])
            ->with([
                'images' => function ($q) {
                    /** @var \yii\db\ActiveQuery $q */
                    $q->orderBy(['sort' => SORT_ASC]);
                }
            ])->all();

        $thumbImg = $this->_thumbSetting->createImgThumb('size-category-product', 'categoryThumb');

        foreach ($products as $key => $product) {
            if (!$product->images) {
                unset($products[$key]);
            }
            $thumbImg->web_dir = $product->getWebDir();
            $product->imagesGallery = $thumbImg->checkFile($product->images[0]->name);
        }

        if (!$products) {
            throw new NotFoundHttpException();
        }

        $dataCategory = new DataCategory();
        $dataCategory->metaTitle = $category->metaTitle ? : $category->name;
        $dataCategory->metaDescription = $category->metaDescription;
        $dataCategory->title = $category->name;
        $dataCategory->products = $products;

        return $dataCategory;
    }

    /**
     * @param $id
     * @return ProductRepository
     * @throws NotFoundHttpException
     * @throws \ImagickException
     * @throws \yii\base\Exception
     */
    public function getProduct($id_alias)
    {
        if (!ProductRepository::find()->where(['id' => $id_alias])->orWhere(['alias' => $id_alias])->count()) {
            throw new NotFoundHttpException();
        }

        $product = ProductRepository::find()
            ->where(['id' => $id_alias])
            ->orWhere(['alias' => $id_alias])
            ->with([
                'images' => function ($q) {
                    /** @var \yii\db\ActiveQuery $q */
                    $q->orderBy(['sort' => SORT_ASC]);
                }
            ])->one();

        if (!$product->images) {
            throw new NotFoundHttpException();
        }

        $thumbImg = $this->_thumbSetting->createImgThumb('product-image', 'productThumb');
        $thumbImg->web_dir = $product->getWebDir();

        foreach ($product->images as $image) {
            $product->imagesGallery[] = $thumbImg->checkFile($image->name);
        }

        return $product;
    }

}