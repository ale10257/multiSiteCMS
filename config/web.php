<?php

$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'bootstrap' => [
        'log',
        'app\config\bootstrap\SetUp'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => SITE_ROOT_NAME
        ],
        'user' => [
            'class' => 'app\components\user\User',
            'identityClass' => 'app\core\user\entities\user\Identity',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/login' => 'admin/auth/login',
                'admin' => 'admin/default',
                'login' => 'login/index',
                'logout' => 'login/logout',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Admin',
            'controllerMap' => [
                'menu-admin' => 'app\modules\admin\controllers\menuAdmin\MenuAdminController',
                'user-admin' => 'app\modules\admin\controllers\menuAdmin\UserAdminController',
                'sites-admin' => 'app\modules\admin\controllers\menuAdmin\SitesAdminController',
                'permit-admin' => 'app\modules\admin\controllers\menuAdmin\PermitAdminController',
                'auth' => 'app\modules\admin\controllers\auth\AuthController',
            ],
        ],
        'feedback' => [
            'class' => 'app\modules\feedback\Feedback',
        ],
    ],
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'custom_model' => '@app/core/other/modelTemplateCrud/default',
                ]
            ]
        ]
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
