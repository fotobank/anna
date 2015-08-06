<?php
/**
 * Класс proxy Cookie
 *
 * @created   by PhpStorm
 * @package   Cookie.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:39
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use core\Db\dbObject;
use helper\Cookie\Cookie as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($name)
 * @see      proxy\Cookie::get()
 *
 * @method   static bool inc($name, $value=1)
 * @see      proxy\Cookie::inc()
 *
 * @method   static bool dec($name, $value=1)
 * @see      proxy\Cookie::dec()
 *
 * @method   static bool has($name)
 * @see      proxy\Cookie::has()
 *
 * @method   static void del($name)
 * @see      proxy\Cookie::del()
 *
 * @method   static array CookieKeys()
 * @see      proxy\Cookie::CookieKeys()
 *
 * @method   static bool isIn($name, $value)
 * @see      proxy\Cookie::isIn()
 *
 * @method   static string setDelimiter($delimiter = '/')
 * @see      proxy\Cookie::setDelimiter()
 *
 * @method   static string getDelimiter()
 * @see      proxy\Cookie::getDelimiter()
 *
 * @method   static array CookieAll()
 * @see      proxy\Cookie::CookieAll()
 *
 * @method   static array fetchAll()
 * @see      proxy\Cookie::fetchAll()
 *
 * @method   static object|void set($name, $value, $expire  = 0, $path  = '', $domain  = '', $secure  = 0)
 * @see      proxy\Cookie::set()
 *
 */
class Cookie extends AbstractProxy
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