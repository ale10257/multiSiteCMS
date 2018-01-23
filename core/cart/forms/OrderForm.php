<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 9:25
 */

namespace app\core\cart\forms;

use yii\base\Model;

class OrderForm extends Model
{
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $email;
    /** @var string */
    public $phone;
    /** @var string */
    public $address;
    /** @var string */
    public $comment;
    /** @var int */
    public $delivery;
    /** @var bool */
    public $rigid_packing = 1;

    public $delivery_array = [
        'ПЭK' =>'ПЭK',
        'Деловые Линии' =>'Деловые Линии',
        'ЖелДорЭкспедиция' =>'ЖелДорЭкспедиция',
        'Прочие транспортные компании' =>'Прочие транспортные компании',
        'Самовывоз(Москва, "Вернисаж в Измайлово")' =>'Самовывоз(Москва, "Вернисаж в Измайлово")'
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'phone', 'last_name', 'first_name'], 'required'],
            [['email', 'phone', 'last_name', 'first_name', 'delivery', 'comment', 'address'], 'string'],
            ['rigid_packing', 'boolean'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Емайл',
            'phone' => 'Телефон',
            'delivery' => 'Доставка',
            'comment' => 'Комментарий к заказу',
            'address' => 'Адрес доставки',
            'rigid_packing' => 'Использовать твердую упаковку? (рекомендуется)',
        ];
    }


}