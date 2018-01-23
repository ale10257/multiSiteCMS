<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 7:42
 */

namespace app\core\user\auth;

use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }
}
