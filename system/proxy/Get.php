<?php
/**
 * Класс proxy Get
 *
 * @created   by PhpStorm
 * @package   Get.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:32
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Get\Get as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($key)
 * @see      proxy\Get::get()
 *
 * @method   static array set($key, $value)
 * @see      proxy\Get::set()
 *
 * @method   static bool inc($key, $value=1)
 * @see      proxy\Get::inc()
 *
 * @method   static bool dec($key, $value=1)
 * @see      proxy\Get::dec()
 *
 * @method   static bool has($key)
 * @see      proxy\Get::has()
 *
 * @method   static void del($key)
 * @see      proxy\Get::del()
 *
 * @method   static bool _has($path)
 * @see      proxy\Get::_has()
 *
 * @method   static array getKeys()
 * @see      proxy\Get::getKeys()
 *
 * @method   static array|mixed _get($path = null)
 * @see      proxy\Get::_get()
 *
 * @method   static Session add($name, $value)
 * @see      proxy\Get::add()
 *
 * @method   static bool|mixed find($value)
 * @see      proxy\Get::find()
 *
 * @method   static array flatten($array, $preserve_keys = false)
 * @see      proxy\Get::flatten()
 *
 * @method   static array arrMultiToOne($arr, &$arr_one)
 * @see      proxy\Get::arrMultiToOne()
 *
 * @method   static array array_merge_recursive_distinct(array &$array1, array &$array2)
 * @see      proxy\Get::array_merge_recursive_distinct()
 *
 * @method   static bool isIn($name, $value)
 * @see      proxy\Get::isIn()
 *
 * @method   static string setDelimiter($delimiter = '/')
 * @see      proxy\Get::setDelimiter()
 *
 * @method   static string getDelimiter()
 * @see      proxy\Get::getDelimiter()
 *
 * @method   static array getAll()
 * @see      proxy\Get::getAll()
 *
 * @method   static array fetchAll()
 * @see      proxy\Get::fetchAll()
 *
 * @method   static Session clear()
 * @see      proxy\Get::clear()
 */
class Get extends AbstractProxy
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