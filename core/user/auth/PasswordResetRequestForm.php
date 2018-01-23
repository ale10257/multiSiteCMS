<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.01.18
 * Time: 9:43
 */

namespace app\core\user\auth;

use app\core\user\entities\user\User;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с таким емайлом не найден.'
            ],
        ];
    }
}