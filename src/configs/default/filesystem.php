<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   filesystem.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     06.09.2015
 * @time:     0:32
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

return
    [
        'local'   => [
            'path.local.adapter' => SITE_PATH,
        ],
        'dropbox' => [
            'prefix.dir' => '',
            'access.token' => 'VpKgIdfD4ZMAAAAAAACLlumDQQ5RJBfyu6ufHYEtcDOnwagdrDwP2QfyMhuZ97rL',
            'app.secret' => 'ewc69x9phcxrskm',
            'app.key' => 'vs17e81nvxecjjd'
        ],
        'ftp' => [
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',

            /** optional config settings */
            'port' => 21,
            'root' => '/path/to/root',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30
        ]
    ];