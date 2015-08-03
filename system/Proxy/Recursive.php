<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Recursive.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.07.2015
 * @time:     21:31
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Recursive as Instance;


/**
 * Class Recursive
 * @package Proxy
 *
 * @method   static array dir($dir_scan = 'files/portfolio', $mask = '.jpg', $inc_subdir = [], $exc_subdir = [], $multi_arrau = true)
 * @see      proxy\Recursive::dir()
 *
 * @method   static array scanDir($base = '', $arr_mask = [], $inc_dir = [], $exc_dir = [], $multi_arrau = true, &$data = [])
 * @see      proxy\Recursive::scanDir()
 */
class Recursive extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
    }
}