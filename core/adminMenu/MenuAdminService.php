<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.12.17
 * Time: 1:32
 */

namespace app\core\adminMenu;

use app\core\NotFoundException;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;
use yii\web\Request;
use app\core\user\entities\user\User;

class MenuAdminService
{
    /** @var MenuAdminRepository */
    private $repository;
    /** @var MenuAdminForm */
    private $menuAdminForm;
    /** @var MenuAdminRepository */
    private $parent;
    /** @var ManagerInterface */
    private $authManager;
    /** @var \ale10257\ext\ChangeTreeBehavior */
    private $tree;


    /**
     * MenuAdminService constructor.
     * @param ManagerInterface $authManager
     * @param MenuAdminForm $menuForm
     * @param MenuAdminRepository $repository
     */
    public function __construct(ManagerInterface $authManager, MenuAdminForm $menuForm, MenuAdminRepository $repository)
    {
        $this->authManager = $authManager;
        $this->menuAdminForm = $menuForm;
        $this->repository = $repository;
        $this->tree = $this->repository->getTree();
    }

    /**
     * @param MenuAdminForm $menuAdminForm
     * @param int|null $parent_id
     * @throws \Exception
     */
    public function create(MenuAdminForm $menuAdminForm, int $parent_id = null)
    {
        $this->createPermission($menuAdminForm);
        $this->getParentForCreate($parent_id);
        $this->repository->insertValues($menuAdminForm);
        $this->repository->appendTo($this->parent);
    }

    /**
     * @param MenuAdminForm $menuAdminForm
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    private function createPermission(MenuAdminForm $menuAdminForm)
    {
        $permit = $this->authManager->createPermission($menuAdminForm->name);
        $permit->description = $menuAdminForm->description;
        $this->authManager->add($permit);
        if ($menuAdminForm->selectedRoles) {
            foreach ($menuAdminForm->selectedRoles as $select) {
                $role = $this->authManager->getRole($select);
                $this->authManager->addChild($role, $permit);
            }
        }
    }

    /**
     * @param MenuAdminForm $menuAdminForm
     * @param int $id
     * @throws \Exception
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function update(MenuAdminForm $menuAdminForm, int $id)
    {
        $menuAdmin = $this->repository->getItem($id);
        $namePermission = $menuAdmin->name == $menuAdminForm->name ? $menuAdminForm->name : $menuAdmin->name;
        $permit = $this->authManager->getPermission($namePermission);
        $this->authManager->remove($permit);
        $menuAdmin->insertValues($menuAdminForm);
        $menuAdmin->saveItem();
        $this->createPermission($menuAdminForm);
    }

    /**
     * @param int|null $parent_id
     * @return MenuAdminForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getNewForm(int $parent_id = null)
    {
        $this->getParentForCreate($parent_id);
        $this->menuAdminForm->parent = $this->parent->title;
        $this->menuAdminForm->roles = ArrayHelper::getColumn($this->authManager->getRoles(), 'name');

        foreach ($this->menuAdminForm->roles as $key => $role) {
            if (array_key_exists($role, User::RESERVED_ROLES)) {
                unset($this->menuAdminForm->roles[$key]);
            }
        }

        return $this->menuAdminForm;
    }

    /**
     * @param int|null $id
     * @return MenuAdminForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getEditForm(int $id)
    {
        $menuEdit = $this->repository->getItem($id);
        if (!$this->parent = $menuEdit->parents(1)->one()) {
            throw new NotFoundException('Parent is not found');
        }
        $permit = $this->authManager->getPermission($menuEdit->name);
        $this->menuAdminForm->description = $permit->description;
        $this->menuAdminForm->parent = $this->parent->title;
        $this->menuAdminForm->createUpdateForm($menuEdit);
        $roles = ArrayHelper::getColumn($this->authManager->getRoles(), 'name');
        foreach ($roles as $key => $role) {
            if (array_key_exists($role, User::RESERVED_ROLES)) {
                unset($roles[$key]);
            }
            if (array_key_exists($this->menuAdminForm->name, $this->authManager->getPermissionsByRole($role))) {
                $this->menuAdminForm->selectedRoles[] = $role;
            }
        }
        $this->menuAdminForm->roles = $roles;

        return $this->menuAdminForm;
    }

    /**
     * @param Request $post
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function updateTree($post)
    {
        $this->repository->updateTree($post);
        return $this->repository->getTree();
    }

    /**
     * @param int|null $parent_id
     * @throws \yii\web\NotFoundHttpException
     */
    private function getParentForCreate($parent_id)
    {
        if (!$root = $this->repository->getRoot()) {
            $root = $this->repository->createRoot();
        }
        $this->parent = $parent_id === null ? $root : $this->repository->getItem($parent_id);
        if (!$this->parent) {
            throw new NotFoundException('Parent is not found');
        }
    }

    /**
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id): void
    {
        $menuAdmin = $this->repository->getItem($id);
        $permit = $this->authManager->getPermission($menuAdmin->name);
        $this->authManager->remove($permit);
        $menuAdmin->deleteItem();
    }
}
