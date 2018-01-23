<?php

namespace app\tmp;

use Yii;

/**
 * This is the model class for table "reg_users".
 *
 * @property int $id
 * @property int $users_id
 * @property int $post_code
 * @property string $region
 * @property string $city
 * @property string $address
 * @property string $phone
 * @property string $billing_info
 * @property string $site_constant
 *
 * @property Users $users
 */
class RegUsers extends \yii\db\ActiveRecord
{

    /** @var int */ 
    public $id; 
    
    /** @var int */ 
    public $users_id; 
    
    /** @var int */ 
    public $post_code; 
    
    /** @var string */ 
    public $region; 
    
    /** @var string */ 
    public $city; 
    
    /** @var string */ 
    public $address; 
    
    /** @var string */ 
    public $phone; 
    
    /** @var string */ 
    public $billing_info; 
    
    /** @var string */ 
    public $site_constant; 
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reg_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users_id', 'post_code'], 'integer'],
            [['phone', 'site_constant'], 'required'],
            [['billing_info'], 'string'],
            [['region', 'city', 'address', 'phone', 'site_constant'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users_id' => 'Users ID',
            'post_code' => 'Post Code',
            'region' => 'Region',
            'city' => 'City',
            'address' => 'Address',
            'phone' => 'Phone',
            'billing_info' => 'Billing Info',
            'site_constant' => 'Site Constant',
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
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'users_id']);
    }

}
