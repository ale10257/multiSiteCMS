<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.12.17
 * Time: 8:58
 */

namespace app\core\permissionsAndRoles;

use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;

class PermissionsAndRoleService
{
    private $manager;

    /**
     * PermissionsAndRoleService constructor.
     * @param ManagerInterface $manager
     */
    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param FormPermissionsAndRole $form
     * @throws \yii\base\Exception
     */
    public function update(FormPermissionsAndRole $form)
    {
        $parent = $this->manager->getRole($form->role);
        $this->manager->removeChildren($parent);

        if ($form->selectedPermissions) {
            foreach ($form->selectedPermissions as $item) {
                $permit = $this->manager->getPermission($item);
                $this->manager->addChild($parent, $permit);
            }
        }
    }

    /**
     * @param string $role
     * @return FormPermissionsAndRole
     */
    public function getForm(string $role)
    {
        if (!$permissions = $this->manager->getPermissions()) {
            throw new \DomainException('Permissions not found!');
        }
        $rolePermit = $this->manager->getPermissionsByRole($role);
        $form = new FormPermissionsAndRole();
        foreach ($permissions as $permit) {
            if (array_key_exists($permit->name, $rolePermit)) {
                $form->selectedPermissions[] = $permit->name;
            }
        }
        $form->permissions = ArrayHelper::map($permissions, 'name', 'name');
        $form->role = $role;
        return $form;
    }
}