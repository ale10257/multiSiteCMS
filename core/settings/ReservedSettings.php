<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.12.17
 * Time: 7:23
 */

namespace app\core\settings;


class ReservedSettings
{
    const MAIL_ADMIN = 'mail_admin';
    const LOGIN_EMAIL = 'login_email';
    const PASSWD_EMAIL = 'passwd_email';

    const RESERVED_SETTINGS = [
        'mail_admin' => [
            'name' => 'Почта для отправки сообщений с сайта',
            'alias' => self::MAIL_ADMIN,
            'value' => 'email@email.ru',
            'active' => 1,
            'childs' => [
                [
                    'name' => 'Логин',
                    'alias' => self::LOGIN_EMAIL,
                    'value' => 'email@email.ru',
                    'active' => 1,
                ],
                [
                    'name' => 'Пароль',
                    'alias' => self::PASSWD_EMAIL,
                    'value' => '',
                    'active' => 1,
                ]
            ],
        ],
        'google_captcha' => [
            'name' => 'Google captcha',
            'alias' => 'google_captcha',
            'value' => 'no-value',
            'active' => 0,
            'childs' => [
                [
                    'name' => 'Secret Key',
                    'alias' => 'secret_key',
                    'value' => 'no-value',
                    'active' => 0,
                ],
                [
                    'name' => 'Site key',
                    'alias' => 'site_key',
                    'value' => 'no-value',
                    'active' => 0,
                ]

            ],
        ],
        'preview-gallery' => [
            'name' => 'Размеры картинок в галереях',
            'alias' => 'preview-gallery',
            'value' => 'no-value',
            'active' => 1,
            'childs' => [
                [
                    'name' => 'Ширина',
                    'alias' => 'width',
                    'value' => '200',
                    'active' => 1,
                ],
                [
                    'name' => 'Высота',
                    'alias' => 'height',
                    'value' => '150',
                    'active' => 1,
                ]

            ],
        ]
    ];
}