<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 7:42
 */

namespace app\core\user\auth;

use yii\base\Model;

class LoginEmailForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }
}
