<?php
/**
 * Класс Session
 * @created   by PhpStorm
 * @package   Session.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     30.07.2015
 * @time:     21:53
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Session\Check\Session as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($key)
 * @see      proxy\Session::get()
 *
 * @method   static array set($key, $value)
 * @see      proxy\Session::set()
 *
 * @method   static bool inc($key, $value=1)
 * @see      proxy\Session::inc()
 *
 * @method   static bool dec($key, $value=1)
 * @see      proxy\Session::dec()
 *
 * @method   static bool has($key)
 * @see      proxy\Session::has()
 *
 * @method   static void del($key)
 * @see      proxy\Session::del()
 *
 * @method   static bool _has($path)
 * @see      proxy\Session::_has()
 *
 * @method   static array getKeys()
 * @see      proxy\Session::getKeys()
 *
 * @method   static array|mixed _get($path = null)
 * @see      proxy\Session::_get()
 *
 * @method   static Session add($name, $value)
 * @see      proxy\Session::add()
 *
 * @method   static bool|mixed find($value)
 * @see      proxy\Session::find()
 *
 * @method   static array flatten($array, $preserve_keys = false)
 * @see      proxy\Session::flatten()
 *
 * @method   static array arrMultiToOne($arr, &$arr_one)
 * @see      proxy\Session::arrMultiToOne()
 *
 * @method   static array array_merge_recursive_distinct(array &$array1, array &$array2)
 * @see      proxy\Session::array_merge_recursive_distinct()
 *
 * @method   static bool isIn($name, $value)
 * @see      proxy\Session::isIn()
 *
 * @method   static string setDelimiter($delimiter = '/')
 * @see      proxy\Session::setDelimiter()
 *
 * @method   static string getDelimiter()
 * @see      proxy\Session::getDelimiter()
 *
 * @method   static array getAll()
 * @see      proxy\Session::getAll()
 *
 * @method   static array fetchAll()
 * @see      proxy\Session::fetchAll()
 *
 * @method   static Session clear()
 * @see      proxy\Session::clear()
 *
 * @method   static bool sessionExists()
 * @see      proxy\Session::sessionExists()
 *
 * @method   static void start()
 * @see      proxy\Session::start()
 *
 * @method   static string getId()
 * @see      proxy\Session::getId()
 *
 * @method   static bool Session sessionExists()
 * @see      proxy\Session::sessionExists()
 *
 * @method   static destroy()
 * @see      proxy\Session::destroy()
 *
 * @method   static bool cookieExists()
 * @see      proxy\Session::cookieExists()
 *
 * @method   static void expireSessionCookie()
 * @see      proxy\Session::expireSessionCookie()
 *
 * @method   static string getName()
 * @see      proxy\Session::getName()
 *
 * @method   static Session setName($name)
 * @see      proxy\Session::setName()
 *
 * @method   static void setSessionCookieLifetime($ttl)
 * @see      proxy\Session::setSessionCookieLifetime()
 *
 * @method   static bool regenerateId($deleteOldSession = true)
 * @see      proxy\Session::regenerateId()
 *
 * @method   static Session setId($id)
 * @see      proxy\Session::setId()
 */
class Session extends AbstractProxy
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