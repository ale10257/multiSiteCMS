<?php

namespace app\tmp;

use Yii;

/**
 * This is the model class for table "order_products".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $from_stocke
 * @property int $to_order
 * @property string $image
 *
 * @property Orders $order
 * @property Products $product
 */
class OrderProducts extends \yii\db\ActiveRecord
{

    /** @var int */ 
    public $id; 
    
    /** @var int */ 
    public $order_id; 
    
    /** @var int */ 
    public $product_id; 
    
    /** @var int */ 
    public $from_stocke; 
    
    /** @var int */ 
    public $to_order; 
    
    /** @var string */ 
    public $image; 
    

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
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'from_stocke', 'to_order'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'from_stocke' => 'From Stocke',
            'to_order' => 'To Order',
            'image' => 'Image',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

}
