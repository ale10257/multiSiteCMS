<?php
$params = require __DIR__ . '/params.php';
$data_email = require __DIR__ . '/data_email.php';
$params = array_merge($params, $data_email);
require __DIR__ . '/../web.php';
$new_config = [
    'components' => [
        'assetManager' => [
            'linkAssets' => DIRECTORY_SEPARATOR == '/' ? true : false,
            'appendTimestamp' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'startSiteKey',
        ],
        'urlManager' => [
            'rules' => [
                'category/<alias:[\w\d\-_]+>' => 'product/category',
                'article/<alias:[\w\d\-_]+>' => 'article/one-article',
                'articles/<alias:[\w\d\-_]+>' => 'article/any-articles',
            ],
        ],
        'mailer' => [
        ],
    ],
    'name' => $params['name'],
    'params' => $params,
    'controllerNamespace' => 'app\sites\startSite\controllers'
];

return array_replace_recursive($config, $new_config);
