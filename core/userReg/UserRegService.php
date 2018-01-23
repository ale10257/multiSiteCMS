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
    /**
     * @var UserRegRepository
     */
    private $_userRegRepository;

    /**
     * @var ManagerInterface
     */
    private $_userManager;
    /**
     * @var UserAdminCreateForm
     */
    private $_adminCreateForm;
    /**
     * @var UserAdminEditForm
     */
    private $_adminEditForm;
    /**
     * @var UserRepository
     */
    private $_userRepository;
    /**
     * @var User
     */
    private $_user;

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
        $this->_userRegRepository = $repository;
        $this->_userManager = $userManager;
        $this->_adminCreateForm = $adminCreateForm;
        $this->_adminEditForm = $adminEditForm;
        $this->_userRepository = $userRepository;
        $this->_user = $user;
    }

    /**
     * @param UserRegForm $form
     * @return User
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function create(UserRegForm $form)
    {
        $this->_user = $this->_user::create($form->user);
        $this->_userRepository->save($this->_user);

        $role = $this->_userManager->getRole('reg_user');
        $this->_userManager->assign($role, $this->_user->id);

        $form->users_id = $this->_user->id;
        $this->_userRegRepository->insertValues($form);
        $this->_userRegRepository->saveItem();

        return $this->_user;
    }

    /**
     * @param UserRegForm $form
     * @param int $id
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(UserRegForm $form, int $id)
    {
        $user = $this->_userRepository->get($form->users_id);
        $user = $user->edit($form->user, $user);
        $this->_userRepository->save($user);

        $this->_userRegRepository = $this->_userRegRepository->getItem($id);
        $this->_userRegRepository->insertValues($form);
        $this->_userRegRepository->saveItem();
    }

    /**
     * @return UserRegForm
     */
    public function getNewForm()
    {
        $form = new UserRegForm($this->_adminCreateForm);
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
        $this->_userRegRepository = $this->_userRegRepository->getItem($id);
        $user = $this->_userRepository->get($this->_userRegRepository->users_id);
        $this->_adminEditForm->createUpdateForm($user);
        $form = new UserRegForm($this->_adminEditForm);
        $form->createUpdateForm($this->_userRegRepository);

        return $form;
    }

    public function getIdRegUser($id)
    {
        if (!$user = $this->_userRegRepository::findOne(['users_id' => $id])) {
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
        $this->_userRegRepository = $this->_userRegRepository->getItem($id);
        $user = $this->_userRepository->get($this->_userRegRepository->users_id);
        $this->_userRepository->remove($user);
    }
}