<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 19:26
 */

namespace app\core\user\forms;

use app\core\other\helpers\InsertValuesHelper;
use app\core\user\entities\user\User;

class UserAdminEditForm extends UserAdminCreateForm implements UserFormInterface
{
    /** @var int */
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'role'], 'required'],
            [['first_name', 'last_name', 'email', 'role', 'passwd'], 'trim'],
            [['passwd'], 'string', 'min' => 6],
            [['status'], 'integer'],
            [['login', 'first_name', 'last_name'], 'string', 'max' => 255],
            [['email', 'login'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->id]],
            [['email'], 'email'],
        ];
    }

    /**
     * @param User $user
     */
    public function createUpdateForm(User $user)
    {
        InsertValuesHelper::insertValues($this, $user, [
            'id',
            'role',
            'login',
            'email',
            'last_name',
            'first_name',
            'status',
        ]);
    }

}