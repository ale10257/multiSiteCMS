<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 6:39
 */

namespace app\core\user\repositories;
use app\core\user\entities\user\User;

class UserReadRepository
{
    /**
     * @param $id
     * @return User|null
     */
    public static function find($id): ?User
    {
        return User::findOne($id);
    }

    /**
     * @param $login
     * @return User|null
     */
    public static function findActiveByLogin($login): ?User
    {
        return User::findOne(['login' => $login, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * @return User|null
     */
    public static function findNoRegUser(): ?User
    {
        return User::findOne(['login' => User::RESERVED_ROLES['no_reg']]);
    }
    /**
     * @param $id
     * @return User|null
     */
    public static function findActiveById($id): ?User
    {
        return User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE]);
    }
}
