<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 7:47
 */

namespace app\core\user\auth;

use app\core\user\entities\user\User;
use app\core\user\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;


class AuthService
{
    /** @var UserRepository  */
    private $repository;
    /** @var MailerInterface */
    private $mailer;
    /** @var User */
    private $user;

    /**
     * AuthService constructor.
     * @param User $user
     * @param UserRepository $repository
     * @param MailerInterface $mailer
     */
    public function __construct(User $user, UserRepository $repository, MailerInterface $mailer)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
        $this->user = $user;
    }

    /**
     * @param LoginForm $form
     * @return User
     */
    public function auth(LoginForm $form): User
    {
        $user = $this->repository->getByLogin($form->login);

        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Неверный логин, или пароль.');
        }

        return $user;
    }

    /**
     * @param LoginEmailForm $form
     * @return User
     */
    public function authRegUser(LoginEmailForm $form): User
    {
        $user = $this->repository->getByEmailRegUser($form->email);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new \DomainException('Неверный логин, или пароль.');
        }

        return $user;
    }

    /**
     * @param PasswordResetRequestForm $form
     * @param string $adminEmail
     * @param bool|null $regUser
     * @throws \yii\base\Exception
     */
    public function sendEmailResetPassword(PasswordResetRequestForm $form, string $adminEmail, bool $regUser = null)
    {
       if ($regUser === null) {
           $user = $this->repository->getByEmail($form->email);
       } else {
           $user = $this->repository->getByEmailRegUser($form->email);
       }

        if (User::isPasswordResetTokenValid($user->password_reset_token)) {
            throw new \DomainException('Письмо для восстановления пароля вам уже отправлено!');
        }

        $url = $user->role == 'reg_user' ? 'login' : 'admin/auth';
        $user->generatePasswordResetToken();
        $this->repository->save($user);

         $send = $this->mailer
            ->compose(
                ['html' => 'passwordResetToken',],
                ['user' => $user, 'url' => $url]
            )
            ->setFrom($adminEmail)
            ->setTo($form->email)
            ->setSubject('Восстановление пароля для сайта ' . Yii::$app->name)
            ->send();

         if (!$send) {
             throw new \DomainException('Неизвестная ошибка при отправке письма. Попробуйте позже.');
         }
    }

    /**
     * @param ResetPasswordForm $form
     * @param string $token
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function resetPassword(ResetPasswordForm $form, string $token)
    {
        if (!$user = $this->user::findByPasswordResetToken($token)) {
            throw new \DomainException('Token is not valide!');
        }
        $user->setPassword($form->password);
        $user->password_reset_token = '';

        $this->repository->save($user);

        return $user;
    }
}
