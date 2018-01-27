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
    private $_repository;
    /** @var MenuAdminForm */
    private $_menuAdminForm;
    /** @var MenuAdminRepository */
    private $_parent;
    /** @var ManagerInterface */
    private $_authManager;
    /** @var \ale10257\ext\ChangeTreeBehavior */
    private $_tree;

    /**
     * MenuAdminService constructor.
     * @param ManagerInterface $authManager
     */
    public function __construct(ManagerInterface $authManager)
    {
        $this->_authManager = $authManager;
        $this->_menuAdminForm = new MenuAdminForm();
        $this->_repository = new MenuAdminRepository();
        $this->_tree = $this->_repository->getTree();
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
        $this->_repository->insertValues($menuAdminForm);
        $this->_repository->appendTo($this->_parent);
    }

    /**
     * @param MenuAdminForm $menuAdminForm
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    private function createPermission(MenuAdminForm $menuAdminForm)
    {
        $permit = $this->_authManager->createPermission($menuAdminForm->name);
        $permit->description = $menuAdminForm->description;
        $this->_authManager->add($permit);
        if ($menuAdminForm->selectedRoles) {
            foreach ($menuAdminForm->selectedRoles as $select) {
                $role = $this->_authManager->getRole($select);
                $this->_authManager->addChild($role, $permit);
            }
        }
    }

    /**
     * @param MenuAdminForm $menuAdminForm
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function update(MenuAdminForm $menuAdminForm, int $id)
    {
        $menuAdmin = $this->_repository->getItem($id);
        $namePermission = $menuAdmin->name == $menuAdminForm->name ? $menuAdminForm->name : $menuAdmin->name;
        $permit = $this->_authManager->getPermission($namePermission);
        $this->_authManager->remove($permit);
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
        $this->_menuAdminForm->parent = $this->_parent->title;
        $this->_menuAdminForm->roles = ArrayHelper::getColumn($this->_authManager->getRoles(), 'name');

        foreach ($this->_menuAdminForm->roles as $key => $role) {
            if (array_key_exists($role, User::RESERVED_ROLES)) {
                unset($this->_menuAdminForm->roles[$key]);
            }
        }

        return $this->_menuAdminForm;
    }

    /**
     * @param int|null $id
     * @return MenuAdminForm
     * @throws \yii\web\NotFoundHttpException
     */
    public function getEditForm(int $id)
    {
        $menuEdit = $this->_repository->getItem($id);
        if (!$this->_parent = $menuEdit->parents(1)->one()) {
            throw new NotFoundException('Parent is not found');
        }
        $permit = $this->_authManager->getPermission($menuEdit->name);
        $this->_menuAdminForm->description = $permit->description;
        $this->_menuAdminForm->parent = $this->_parent->title;
        $this->_menuAdminForm->createUpdateForm($menuEdit);
        $roles = ArrayHelper::getColumn($this->_authManager->getRoles(), 'name');
        foreach ($roles as $key => $role) {
            if (array_key_exists($role, User::RESERVED_ROLES)) {
                unset($roles[$key]);
            }
            if (array_key_exists($this->_menuAdminForm->name, $this->_authManager->getPermissionsByRole($role))) {
                $this->_menuAdminForm->selectedRoles[] = $role;
            }
        }
        $this->_menuAdminForm->roles = $roles;

        return $this->_menuAdminForm;
    }

    /**
     * @param Request $post
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function updateTree($post)
    {
        $this->_repository->updateTree($post);

        return $this->_repository->getTree();
    }

    /**
     * @param int|null $parent_id
     * @throws \yii\web\NotFoundHttpException
     */
    private function getParentForCreate($parent_id)
    {
        if (!$root = $this->_repository->getRoot()) {
            $root = $this->_repository->createRoot();
        }
        $this->_parent = $parent_id === null ? $root : $this->_repository->getItem($parent_id);
        if (!$this->_parent) {
            throw new NotFoundException('Parent is not found');
        }
    }

    /**
     * @return \ale10257\ext\ChangeTreeBehavior
     */
    public function getTree()
    {
        return $this->_tree;
    }

    /**
     * @param int $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id): void
    {
        $menuAdmin = $this->_repository->getItem($id);
        $permit = $this->_authManager->getPermission($menuAdmin->name);
        $this->_authManager->remove($permit);
        $menuAdmin->deleteItem();
    }
}
