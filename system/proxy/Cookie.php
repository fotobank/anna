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

use helper\Cookie\Cookie as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($key)
 * @see      proxy\Cookie::get()
 *
 * @method   static bool inc($key, $value=1)
 * @see      proxy\Cookie::inc()
 *
 * @method   static bool dec($key, $value=1)
 * @see      proxy\Cookie::dec()
 *
 * @method   static bool has($key)
 * @see      proxy\Cookie::has()
 *
 * @method   static void del($key)
 * @see      proxy\Cookie::del()
 *
 * @method   static bool _has($path)
 * @see      proxy\Cookie::_has()
 *
 * @method   static array CookieKeys()
 * @see      proxy\Cookie::CookieKeys()
 *
 * @method   static array|mixed _get($path = null)
 * @see      proxy\Cookie::__get()
 *
 * @method   static bool|mixed find($value)
 * @see      proxy\Cookie::find()
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
 * @method   static Session clear()
 * @see      proxy\Cookie::clear()
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