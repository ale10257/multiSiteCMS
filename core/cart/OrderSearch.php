<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.01.18
 * Time: 8:09
 */

namespace app\core\cart;

use app\core\cart\repositories\OrderRepository;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrderSearch extends Model
{
    /** @var string */
    public $full_name;
    /** @var string */
    public $phone;
    /** @var string */
    public $email;
    /** @var array */
    public $status_data;
    /** @var string */
    public $status;
    /** @var int */
    public $all_sum;
    /** @var int */
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'all_sum'], 'integer'],
            [['full_name', 'id',], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OrderRepository::find()->alias('o')->joinWith(['user u'])->where(['o.site_constant' => SITE_ROOT_NAME]);

        $this->status_data = [
            OrderRepository::STATUS_ORDER_ERROR_TIMEOUT => [
                'status' => OrderRepository::STATUS_ORDER_ERROR_TIMEOUT,
                'title' =>'Висит',
                'class' => 'warning',
            ],
            OrderRepository::STATUS_ORDER_CREATION => [
                'status' => OrderRepository::STATUS_ORDER_CREATION,
                'title' =>'Создается',
                'class' => 'danger',
            ],
            OrderRepository::STATUS_ORDER_NOT_VERIFED => [
                'status' => OrderRepository::STATUS_ORDER_NOT_VERIFED,
                'title' =>'Не подтвержден',
                'class' => 'info',
            ],
            OrderRepository::STATUS_ORDER_VERIFED => [
                'status' => OrderRepository::STATUS_ORDER_VERIFED,
                'title' =>'Подтвержден',
                'class' => 'primary',
            ],
            OrderRepository::STATUS_ORDER_CLOSED => [
                'status' => OrderRepository::STATUS_ORDER_CLOSED,
                'title' =>'Отправлен',
                'class' => 'success',
            ],
        ];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['status', 'updated_at', 'all_sum'],
                'defaultOrder' => ['updated_at' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'o.id' => $this->id,
        ]);

        $query->andFilterWhere([
            'o.status' => $this->status,
        ]);

        $query->joinWith([
            'user u' => function ($q) {
                /** @var $q \yii\db\ActiveQuery */
                $q->where('u.first_name LIKE "%' . $this->full_name . '%"')->orWhere('u.last_name LIKE "%' . $this->full_name . '%"');
            }
        ,]);

        return $dataProvider;
    }

}