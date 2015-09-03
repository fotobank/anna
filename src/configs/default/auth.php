<?php
/**
 *  config file auth.php
 *
 * @created   by PhpStorm
 * @package   auth.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     19.08.2015
 * @time:     20:29
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


return [
    'auth' => [
        'equals' => [
            'hash'            => function ($password)
            {
                return password_hash($password, PASSWORD_DEFAULT);
            },
            'verify'          => function ($password, $hash)
            {
                return password_verify($password, $hash);
            },
            'encryptFunction' => function ($password, $salt = SALT)
            {
                return password_hash($password, PASSWORD_DEFAULT, ['salt' => $salt]);
            },
        ],
    ],
];