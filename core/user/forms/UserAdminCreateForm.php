<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 19:26
 */

namespace app\core\user\forms;

use app\core\user\entities\user\User;
use yii\base\Model;

class UserAdminCreateForm extends Model implements UserFormInterface
{
    /** @var string */
    public $first_name;
    /** @var string */
    public $last_name;
    /** @var string */
    public $email;
    /** @var int */
    public $status;
    /** @var string */
    public $passwd;
    /** @var string */
    public $login;
    /** @var string */
    public $role;
    /** @var array */
    public $roles = [];
    /** @var array */
    public $statuses = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'role', 'passwd'], 'required'],
            [['first_name', 'last_name', 'email', 'role', 'passwd'], 'trim'],
            [['status'], 'integer'],
            ['email', 'email'],
            [['passwd'], 'string', 'min' => 6],
            [['login', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['email', 'login'], 'unique', 'targetClass' => User::class],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'role' => 'Роль',
            'email' => 'Email',
            'status' => 'Active',
            'roles' => 'Роли',
            'passwd' => 'Пароль',
        ];
    }
}