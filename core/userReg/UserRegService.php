<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.12.17
 * Time: 17:11
 */

namespace app\core\userReg;

use app\core\NotFoundException;
use app\core\user\entities\user\User;
use app\core\user\forms\UserAdminCreateForm;
use app\core\user\forms\UserAdminEditForm;
use app\core\user\repositories\UserRepository;
use yii\rbac\ManagerInterface;

class UserRegService
{
    /** @var UserRegRepository */
    private $userRegRepository;
    /** @var ManagerInterface */
    private $userManager;
    /** @var UserAdminCreateForm */
    private $adminCreateForm;
    /** @var UserAdminEditForm */
    private $adminEditForm;
    /** @var UserRepository */
    private $userRepository;
    /** @var User */
    private $user;

    /**
     * @param ManagerInterface $userManager
     * @param UserRegRepository $repository
     * @param UserAdminCreateForm $adminCreateForm
     * @param UserAdminEditForm $adminEditForm
     * @param UserRepository $userRepository
     * @param User $user
     */
    public function __construct(
        ManagerInterface $userManager,
        UserRegRepository $repository,
        UserAdminCreateForm $adminCreateForm,
        UserAdminEditForm $adminEditForm,
        UserRepository $userRepository,
        User $user
    )
    {
        $this->userRegRepository = $repository;
        $this->userManager = $userManager;
        $this->adminCreateForm = $adminCreateForm;
        $this->adminEditForm = $adminEditForm;
        $this->userRepository = $userRepository;
        $this->user = $user;
    }

    /**
     * @param UserRegForm $form
     * @return User
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function create(UserRegForm $form)
    {
        $this->user = $this->user::create($form->user);
        $this->userRepository->save($this->user);

        $role = $this->userManager->getRole('reg_user');
        $this->userManager->assign($role, $this->user->id);

        $form->users_id = $this->user->id;
        $this->userRegRepository->insertValues($form);
        $this->userRegRepository->saveItem();

        return $this->user;
    }

    /**
     * @param UserRegForm $form
     * @param int $id
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(UserRegForm $form, int $id)
    {
        $user = $this->userRepository->get($form->users_id);
        $user = $user->edit($form->user, $user);
        $this->userRepository->save($user);

        $this->userRegRepository = $this->userRegRepository->getItem($id);
        $this->userRegRepository->insertValues($form);
        $this->userRegRepository->saveItem();
    }

    /**
     * @return UserRegForm
     */
    public function getNewForm()
    {
        $form = new UserRegForm($this->adminCreateForm);
        $form->user->role = 'reg_user';
        return $form;
    }

    /**
     * @param int $id
     * @return UserRegForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getUpdateForm(int $id)
    {
        $this->userRegRepository = $this->userRegRepository->getItem($id);
        $user = $this->userRepository->get($this->userRegRepository->users_id);
        $this->adminEditForm->createUpdateForm($user);
        $form = new UserRegForm($this->adminEditForm);
        $form->createUpdateForm($this->userRegRepository);

        return $form;
    }

    public function getIdRegUser($id)
    {
        if (!$user = $this->userRegRepository::findOne(['users_id' => $id])) {
            throw new NotFoundException('Пользователь не найден');
        }
        return $user->id;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function delete(int $id)
    {
        $this->userRegRepository = $this->userRegRepository->getItem($id);
        $user = $this->userRepository->get($this->userRegRepository->users_id);
        $this->userRepository->remove($user);
    }
}