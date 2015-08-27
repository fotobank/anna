<?php
/**
 * ����� proxy Server
 * @created   by PhpStorm
 * @package   Server.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     03.08.2015
 * @time:     19:53
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Server\Server as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($key)
 * @see      proxy\Server::get()
 *
 * @method   static array set($key, $value)
 * @see      proxy\Server::set()
 *
 * @method   static bool inc($key, $value=1)
 * @see      proxy\Server::inc()
 *
 * @method   static bool dec($key, $value=1)
 * @see      proxy\Server::dec()
 *
 * @method   static bool has($key)
 * @see      proxy\Server::has()
 *
 * @method   static void del($key)
 * @see      proxy\Server::del()
 *
 * @method   static bool _has($path)
 * @see      proxy\Server::_has()
 *
 * @method   static array getKeys()
 * @see      proxy\Server::getKeys()
 *
 * @method   static array|mixed _get($path = null)
 * @see      proxy\Server::_get()
 *
 * @method   static Session add($name, $value)
 * @see      proxy\Server::add()
 *
 * @method   static bool|mixed find($value)
 * @see      proxy\Server::find()
 *
 * @method   static array flatten($array, $preserve_keys = false)
 * @see      proxy\Server::flatten()
 *
 * @method   static array arrMultiToOne($arr, &$arr_one)
 * @see      proxy\Server::arrMultiToOne()
 *
 * @method   static array array_merge_recursive_distinct(array &$array1, array &$array2)
 * @see      proxy\Server::array_merge_recursive_distinct()
 *
 * @method   static bool isIn($name, $value)
 * @see      proxy\Server::isIn()
 *
 * @method   static string setDelimiter($delimiter = '/')
 * @see      proxy\Server::setDelimiter()
 *
 * @method   static string getDelimiter()
 * @see      proxy\Server::getDelimiter()
 *
 * @method   static array getAll()
 * @see      proxy\Server::getAll()
 *
 * @method   static array fetchAll()
 * @see      proxy\Server::fetchAll()
 *
 * @method   static Session clear()
 * @see      proxy\Server::clear()
 */
class Server extends AbstractProxy
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