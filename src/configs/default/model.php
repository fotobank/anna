<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   model.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     17.09.2015
 * @time:     17:42
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use proxy\Cookie;
use proxy\Router;
use proxy\Session;

return

    [
        'section_title'  => [
            '�������'              => '/index',
            '��&nbsp;&nbsp;������' => '/about',
            '���������'            => '/portfolio',
            '�������'              => '/news',
            '������'               => '/services',
            '��������'             => '/comments',
        ],
        'admin_mode'     => if_admin(true),
        // footer
        'debug_mode'     => DEBUG_MODE,
        'auto_copyright' => auto_copyright('2011'),
        'php_sessid'     => function()
        {
            return Cookie::get('PHPSESSID') or ip();
        },
        'current_razdel' => function()
        {
            return strtolower(Router::getCurrentRoute()['controller']);
        },
        // ������ login
        'login'          => function()
        {
            return Session::get('logged');
        }
    ];