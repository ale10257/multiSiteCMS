<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 16:08
 */

namespace app\components\helpers\adminSidebar;

use app\core\adminMenu\MenuAdminRepository;
use yii;

class AdminSidebarTree
{
    /**
     * @return \app\core\adminMenu\MenuAdminRepository[]
     */
    public static function createTree()
    {
        $menuAdmin = new MenuAdminRepository();
        /**
         * @var $tree MenuAdminRepository[]
         */
        $tree = $menuAdmin->getTree();

        $user = yii::$app->user;

        foreach ($tree as $key => $item) {
            if (!$item->show_in_sidebar) {
                unset($tree[$key]);
                continue;
            }
            if (!$user->identity->isRoot()) {
                if (!$user->can($item->name)) {
                    unset($tree[$key]);
                }
            }
        }
        return $tree;
    }
}