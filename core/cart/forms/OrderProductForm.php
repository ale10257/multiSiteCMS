<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 11:02
 */

namespace app\core\cart\forms;

use app\core\cart\repositories\OrderProductRepository;
use app\core\other\helpers\InsertValuesHelper;
use yii\base\Model;

class OrderProductForm extends Model
{
    /** @var int */
    public $id;
    /** @var int */
    public $order_id;
    /** @var int */
    public $product_id;
    /** @var int */
    public $count;
    /** @var string */
    public $image;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'count'], 'integer'],
            [['count',], 'required'],
            [['count'], 'integer', 'min' => 1],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param OrderProductRepository $repository
     */
    public function createUpdateForm(OrderProductRepository $repository)
    {
        InsertValuesHelper::insertValues($this, $repository, [
            'order_id',
            'product_id',
            'count',
            'image',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'count' => 'Кол-во'
        ];
    }
}