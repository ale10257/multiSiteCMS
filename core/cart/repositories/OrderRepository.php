<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 9:21
 */

namespace app\core\cart\repositories;

use app\core\base\BaseRepository;
use app\core\cart\ProductCount;
use app\core\user\entities\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * @property int $id
 * @property int $user_id
 * @property string $data
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $all_sum
 * @property int $all_total
 * @property string $site_constant
 * @property string $ip_address
 *
 * @property OrderProductRepository[] $orderProducts
 * @property User $user
 *
 * @method TimestampBehavior touch(string $attribute)
 */
class OrderRepository extends BaseRepository
{
    const STATUS_ORDER_ERROR_TIMEOUT = -1;
    const STATUS_ORDER_CREATION = 0;
    const STATUS_ORDER_NOT_VERIFED = 10;
    const STATUS_ORDER_VERIFED = 20;
    const STATUS_ORDER_CLOSED = 30;

    const TIME_LIMIT = 3600 * 2;

    /** @var ProductCount */
    public $dataOrder;

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
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class
        ];
    }

    public function insertData($user_id, $data, $all_total, $all_sum)
    {
        $this->user_id = $user_id;
        $this->data = $data;
        $this->all_total = $all_total;
        $this->all_sum = $all_sum;
    }

    public function updateData($all_total, $all_sum)
    {
        $this->all_total = $all_total;
        $this->all_sum = $all_sum;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProductRepository::class, ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @throws \yii\db\Exception
     */
    public static function checkTimeout()
    {
        $time = time() - self::TIME_LIMIT;
        yii::$app->db->createCommand()->update(
            static::tableName(),
            ['status' => self::STATUS_ORDER_ERROR_TIMEOUT],
            ['and', 'status = ' . self::STATUS_ORDER_CREATION, 'updated_at < ' . $time]
        )->execute();
    }

    /**
     * @param $order_id
     * @param $status
     * @throws \yii\db\Exception
     */
    public static function changeStatus($order_id, $status)
    {
        yii::$app->db->createCommand()->update(
            static::tableName(),
            ['status' => $status],
            ['id' => $order_id]
        )->execute();
    }
}