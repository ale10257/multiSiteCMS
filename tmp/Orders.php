<?php

namespace app\tmp;

use Yii;

/**
 * @property int $id
 * @property int $user_id
 * @property string $data
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $all_sum
 * @property int $all_total
 *
 * @property OrderProducts[] $orderProducts
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord
{

    /** @var int */ 
    public $id; 
    
    /** @var int */ 
    public $user_id; 
    
    /** @var string */ 
    public $data; 
    
    /** @var int */ 
    public $status; 
    
    /** @var int */ 
    public $created_at; 
    
    /** @var int */ 
    public $updated_at; 
    
    /** @var int */ 
    public $all_sum; 
    
    /** @var int */ 
    public $all_total; 
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at', 'all_sum', 'all_total'], 'integer'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'data' => 'Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'all_sum' => 'All Sum',
            'all_total' => 'All Total',
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
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProducts::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

}
