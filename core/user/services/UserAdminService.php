<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 19:36
 */

namespace app\core\user\services;

use app\core\user\entities\user\User;
use app\core\user\forms\UserAdminCreateForm;
use app\core\user\forms\UserAdminEditForm;
use app\core\user\forms\UserFormInterface;
use app\core\user\repositories\UserRepository;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;

class UserAdminService
{
    /** @var User */
    private $_user;
    /** @var ManagerInterface */
    private $_authManager;
    /** @var UserRepository */
    private $_userRepository;

    /**
     * UserAdminService constructor.
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->_user = new User();
        $this->_authManager = $manager;
        $this->_userRepository = new UserRepository();
    }

    /**
     * @param UserFormInterface $userAdminForm
     * @throws \Exception
     */
    public function createAdmin(UserFormInterface $userAdminForm)
    {
        /**@var $userAdminForm UserAdminEditForm */
        $user = $this->_user::create($userAdminForm);
        $this->_userRepository->save($user);
        $role = $this->_authManager->getRole($user->role);
        $this->_authManager->assign($role, $user->id);

    }

    /**
     * @param UserFormInterface $userAdminForm
     * @param int $id
     * @throws \Exception
     */
    public function updateAdmin(UserFormInterface $userAdminForm, int $id)
    {
        /**@var $userAdminForm UserAdminEditForm */

        $user = $this->_userRepository->get($id);

        if ($user->role == 'root') {
            $userAdminForm->status = User::STATUS_ACTIVE;
        }

        $this->_userRepository->save($this->_user->edit($userAdminForm, $user));

        if ($user->role !== 'root') {
            $this->_authManager->revokeAll($user->id);
            $role = $this->_authManager->getRole($userAdminForm->role);
            $this->_authManager->assign($role, $user->id);
        }
    }

    /**
     * @param int $id
     * @return UserAdminEditForm
     */
    public function getAdminForm(int $id = null)
    {
        if ($id) {
            $user = $this->_userRepository->get($id);
            $form = new UserAdminEditForm();
            $form->createUpdateForm($user);
        } else {
            $form = new UserAdminCreateForm();
        }
        $form->statuses = [User::NO_ACTIVE, User::STATUS_ACTIVE];
        $form->roles = ArrayHelper::map($this->_authManager->getRoles(), 'name', 'name');
        foreach ($form->roles as $role) {
            if (array_key_exists($role, User::RESERVED_ROLES)) {
                unset($form->roles[$role]);
            }
        }

        return $form;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        $this->_user = $this->_userRepository->get($id);
        if ($this->_user->role == 'root') {
            throw new \DomainException('Суперпользователя root нельзя удалить из системы!');
        }
        $this->_userRepository->remove($this->_user);
    }
}