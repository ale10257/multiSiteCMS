<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 4:34
 */

namespace app\core\products\repositories;

use app\core\base\BaseRepository;
use app\core\other\helpers\InsertValuesHelper;
use app\core\other\traits\Sort;
use app\core\products\forms\ProductImageForm;

/**
 * @property int $id
 * @property string $name
 * @property int $products_id
 * @property string $alt
 * @property string $title_link
 * @property int $sort
 * @property ProductRepository $product
 */
class ProductImagesRepository extends BaseRepository
{
    use Sort;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_images';
    }

    /**
     * @param ProductImageForm $form
     */
    public function insertValues($form)
    {
        if (!$form->sort) {
            $form->sort = $this->getNumLastElement(['products_id' => $form->products_id], 'sort');
        }
        InsertValuesHelper::insertValues($this, $form, [
            'alt',
            'name',
            'products_id',
            'title_link',
            'sort',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductRepository::class, ['id' => 'products_id']);
    }

    public function getWebDir()
    {
        return $this->product->getWebDir();
    }
}