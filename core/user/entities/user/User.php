<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 5:38
 */

namespace app\core\user\entities\user;

use app\core\user\forms\UserAdminEditForm;
use app\core\user\forms\UserFormInterface;
use app\core\userReg\UserRegRepository;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $login
 * @property string $first_name
 * @property string $last_name
 * @property string $role
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 *
 * @property UserRegRepository $regUser
 */


class User extends ActiveRecord
{
    const NO_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const EXRIRE = 3600;

    const RESERVED_ROLES = [
        'root' => 'root',
        'reg_user' => 'reg_user',
        'no_reg' => 'no_reg' // fake role for cart
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @param UserFormInterface $userAdminForm
     * @return User
     * @throws yii\base\Exception
     */
    public static function create(UserFormInterface $userAdminForm): self
    {
        $user = new User();
        /**@var $userAdminForm UserAdminEditForm */
        $user->login = $userAdminForm->login;
        $user->email = $userAdminForm->email;
        $user->last_name = $userAdminForm->last_name;
        $user->first_name = $userAdminForm->first_name;
        $user->role = $userAdminForm->role;
        $user->setPassword($userAdminForm->passwd);
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        return $user;
    }

    /**
     * @param UserFormInterface $userAdminForm
     * @param User $user
     * @return User
     * @throws yii\base\Exception
     */
    public function edit(UserFormInterface $userAdminForm, User $user)
    {
        /**@var $userAdminForm UserAdminEditForm */
        $user->login = $userAdminForm->login;
        $user->email = $userAdminForm->email;
        $user->last_name = $userAdminForm->last_name;
        $user->first_name = $userAdminForm->first_name;
        $user->role = $userAdminForm->role;
        if ($userAdminForm->passwd) {
            $user->setPassword($userAdminForm->passwd);
        }
        $user->status = $userAdminForm->status;
        return $user;
    }

    /**
     * @param $password
     * @throws yii\base\Exception
     */
    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }


    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegUser()
    {
        return $this->hasOne(UserRegRepository::class, ['users_id' => 'id']);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @throws yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function isPasswordResetTokenValid($token)
    {
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        return $timestamp + self::EXRIRE >= time();
    }

    public static function findByEmailCount($email)
    {
        return static::find()->where(['email' => $email, 'status' => self::STATUS_ACTIVE])->count();
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     * @throws yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws yii\base\Exception
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
