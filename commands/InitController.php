<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.18
 * Time: 0:23
 */

namespace app\commands;

use app\core\adminMenu\MenuAdminForm;
use app\core\adminMenu\MenuAdminRepository;
use app\core\user\entities\user\User;
use app\core\user\forms\UserAdminCreateForm;
use yii;
use yii\console\Controller;
use app\core\categories\CategoryRepository;
use app\core\settings\SettingRepository;

class InitController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        defined('SITE_ROOT_NAME') or define('SITE_ROOT_NAME', 'startSite');
    }

    /**
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        yii::$app->cache->flush();

        if ($userRoot = User::findOne(['role' => 'root'])) {
            throw new \DomainException('User with role root already exist');
        }

        $form = new UserAdminCreateForm();

        $form->login = $this->prompt('Enter login for superuser root:', ['required' =>true]);
        $form->email = $this->prompt('Enter email for superuser root:', ['required' =>true]);
        $form->passwd = $this->prompt('Enter password for superuser root (at least 6 characters):', ['required' =>true]);
        $form->first_name = $this->prompt('Enter first name for superuser root:', ['required' =>true]);
        $form->last_name = $this->prompt('Enter last name for superuser root:', ['required' =>true]);
        $form->role = 'root';

        if (!$form->validate()) {
            foreach ($form->firstErrors as $error) {
                echo $error . PHP_EOL;
            }
            exit(1);
        }

        $user = User::create($form);
        $user->save();

        $role = yii::$app->authManager->createRole(User::RESERVED_ROLES['root']);
        $role->description = 'SuperUser';

        yii::$app->authManager->add($role);
        yii::$app->authManager->assign($role, $user->id);

        $role = yii::$app->authManager->createRole('manager');
        yii::$app->authManager->add($role);

        $role = yii::$app->authManager->createRole(User::RESERVED_ROLES['reg_user']);
        yii::$app->authManager->add($role);

        $form = new UserAdminCreateForm();
        $form->login = 'no_reg';
        $form->email = 'no_reg';
        $form->passwd = 1;
        $form->role = 'no_reg';
        $form->first_name = 'no_name';
        $form->last_name = 'no_name';

        $user = new User;
        $user = $user::create($form);
        $user->save();

        $role = yii::$app->authManager->createRole(User::RESERVED_ROLES['no_reg']);
        $role->description = 'Fake role';

        yii::$app->authManager->add($role);
        yii::$app->authManager->assign($role, $user->id);

        $menuAdmin = [
            'default' => [
                'title' => 'Главная',
                'icon' => 'dashboard',
                'show_in_sidebar' => 1,
            ],
            'order' => [
                'title' => 'Заказы',
                'icon' => 'shopping-cart',
                'show_in_sidebar' => 1,
            ],
            'category' => [
                'title' => 'Категории',
                'icon' => 'folder',
                'show_in_sidebar' => 1,
            ],
            'article' => [
                'title' => 'Статьи',
                'icon' => 'newspaper-o',
                'show_in_sidebar' => 1,
            ],
            'product' => [
                'title' => 'Продукты',
                'icon' => 'product-hunt',
                'show_in_sidebar' => 1,
            ],
            'gallery' => [
                'title' => 'Галереи',
                'icon' => 'file-image-o',
                'show_in_sidebar' => 1,
            ],
            'clear-cache' => [
                'title' => 'Сбросить cache',
                'icon' => '',
                'show_in_sidebar' => 0,
            ],
            'setting' => [
                'title' => 'Настройки',
                'icon' => 'cog',
                'show_in_sidebar' => 1,
            ],
            'discount' => [
                'title' => 'Скидки',
                'icon' => 'tags',
                'show_in_sidebar' => 1,
            ],
            'chunk' => [
                'title' => 'Чанки',
                'icon' => 'code',
                'show_in_sidebar' => 1,
            ],
            'reg-user' => [
                'title' => 'Клиенты',
                'icon' => 'user',
                'show_in_sidebar' => 1,
            ],
        ];

        $menuRepository = new MenuAdminRepository();
        $root = $menuRepository->createRoot();

        foreach ($menuAdmin as $key => $menu) {
            $form = new MenuAdminForm();
            $menuRepository = new MenuAdminRepository();
            $form->name = $key;
            $form->title = $menu['title'];
            $form->icon = $menu['icon'];
            $form->show_in_sidebar = $menu['show_in_sidebar'];
            $menuRepository->insertValues($form);
            $menuRepository->appendTo($root);

            $permit = yii::$app->authManager->createPermission($form->name);
            yii::$app->authManager->add($permit);
        }

        CategoryRepository::createRoot();
        SettingRepository::createRoot();

        $this->prompt('The application was successfully initiated.' . PHP_EOL . 'To enter the admin panel go to admin/login');

        exit(0);
    }
}