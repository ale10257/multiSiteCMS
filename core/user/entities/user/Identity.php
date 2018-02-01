<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 6:33
 */

namespace app\core\user\entities\user;

use app\core\user\repositories\UserReadRepository;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * Class Identity
 * @package app\corev2\user\entities\user
 */
class Identity implements IdentityInterface
{
    /** @var User */
    private $user;
    
    /**
     * Identity constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int|string $id
     * @return Identity|null|IdentityInterface
     */
    public static function findIdentity($id)
    {
        $user = UserReadRepository::findActiveById($id);
        return $user ? new self($user): null;
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getRole(): string
    {
        return $this->user->role;
    }

    public function getLogin(): string
    {
        return $this->user->login;
    }

    public function getEmail(): string
    {
        return $this->user->email;
    }

    public function getFirstName(): string
    {
        return $this->user->first_name;
    }

    public function getLastName(): string
    {
        return $this->user->last_name;
    }

    public function isRoot(): string
    {
        return $this->getRole() === 'root';
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
}
