<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 9:57
 */

namespace app\commands;


use Yii;
use yii\console\Controller;

class AddRoleController extends Controller
{
    /**
     * @throws \Exception
     */
    public function actionIndex()
    {
        $roleName = $this->prompt('Enter new role name', ['required' => true]);
        $desc = $this->prompt('Enter description for new role', ['required' => false]);
        $role = yii::$app->authManager->createRole($roleName);
        $role->description = $desc;
        yii::$app->authManager->add($role);
    }
}