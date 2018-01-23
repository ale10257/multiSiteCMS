<?php

namespace app\core\user\repositories;

use app\core\user\entities\user\User;
use app\core\NotFoundException;

class UserRepository
{
    /**
     * @param $login
     * @return User|null
     */
    public function getByLogin($login): ?User
    {
        return $this->getBy(['login' => $login]);
    }

    /**
     * @param $email
     * @return User|null
     */
    public function getByEmailRegUser($email): ?User
    {
        return $this->getBy(['role' => 'reg_user', 'email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param User $user
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    /**
     * @param array $condition
     * @return User
     */
    private function getBy(array $condition): User
    {
        if (!$user = User::find()->where($condition)->one()) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }
}
