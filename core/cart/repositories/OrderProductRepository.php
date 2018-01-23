<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 9:22
 */

namespace app\core\cart\repositories;

use app\core\base\BaseRepository;
use app\core\cart\forms\OrderProductForm;
use app\core\other\helpers\InsertValuesHelper;
use app\core\products\repositories\ProductRepository;

/**
 * This is the model class for table "order_products".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $count
 * @property string $image
 *
 * @property ProductRepository $product
 * @property OrderProductRepository $order
 */
class OrderProductRepository extends BaseRepository
{
    /** @var OrderProductForm */
    public $form;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_products';
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param $form
     */
    public function insertValues($form)
    {
        InsertValuesHelper::insertValues($this, $form, [
            'order_id',
            'product_id',
            'count',
            'image',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderProductRepository::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductRepository::className(), ['id' => 'product_id']);
    }
}