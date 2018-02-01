<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.12.17
 * Time: 19:40
 */

namespace app\config\bootstrap;

use app\core\cart\forms\OrderFormService;
use app\core\cart\OrderCheckService;
use app\core\cart\repositories\OrderRepository;
use app\core\user\services\CheckCan;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;
use app\core\cache\CacheEntity;
use app\core\settings\SettingService;
use app\core\cart\OrderService;
use app\core\discounts\GetDiscount;

class SetUp implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \WebApplication $app the application currently running
     */
    public function bootstrap($app): void
    {
        $container = \Yii::$container;
        
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(CheckCan::class, function () use ($app) {
            return new CheckCan($app->user);
        });

        $container->setSingleton(CacheEntity::class, function () use ($app) {
            return new CacheEntity($app->cache);
        });

        $container->setSingleton(SettingService::class, function () use ($app) {
            return new SettingService(new CacheEntity($app->cache));
        });

        $container->setSingleton(OrderService::class, function () use ($app) {
            return new OrderService($app->session, $app->user, new OrderRepository());
        });

        $container->setSingleton(GetDiscount::class, function () use ($app) {
            return new GetDiscount(new CacheEntity($app->cache));
        });

        $container->setSingleton(OrderCheckService::class, function () use ($app) {
            return new OrderCheckService($app->session, $app->user, new GetDiscount(new CacheEntity($app->cache)), new OrderRepository());
        });

        $container->setSingleton(OrderFormService::class, function () use ($app) {
            return new OrderFormService($app->user);
        });
    }
}
