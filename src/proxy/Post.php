<?php
/**
 * Класс proxy Post
 *
 * @created   by PhpStorm
 * @package   Post.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:33
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Post\Post as Instance;


/**
 * Proxy Session
 *
 * Class Session
 * @package Proxy
 *
 * @method   static bool|null get($key)
 * @see      proxy\Post::get()
 *
 * @method   static array set($key, $value)
 * @see      proxy\Post::set()
 *
 * @method   static bool inc($key, $value=1)
 * @see      proxy\Post::inc()
 *
 * @method   static bool dec($key, $value=1)
 * @see      proxy\Post::dec()
 *
 * @method   static bool has($key=null)
 * @see      proxy\Post::has()
 *
 * @method   static void del($key)
 * @see      proxy\Post::del()
 *
 * @method   static bool _has($path)
 * @see      proxy\Post::_has()
 *
 * @method   static array getKeys()
 * @see      proxy\Post::getKeys()
 *
 * @method   static array|mixed _get($path = null)
 * @see      proxy\Post::_get()
 *
 * @method   static Session add($name, $value)
 * @see      proxy\Post::add()
 *
 * @method   static bool|mixed find($value)
 * @see      proxy\Post::find()
 *
 * @method   static array flatten($array, $preserve_keys = false)
 * @see      proxy\Post::flatten()
 *
 * @method   static array arrMultiToOne($arr, &$arr_one)
 * @see      proxy\Post::arrMultiToOne()
 *
 * @method   static array array_merge_recursive_distinct(array &$array1, array &$array2)
 * @see      proxy\Post::array_merge_recursive_distinct()
 *
 * @method   static bool isIn($name, $value)
 * @see      proxy\Post::isIn()
 *
 * @method   static string setDelimiter($delimiter = '/')
 * @see      proxy\Post::setDelimiter()
 *
 * @method   static string getDelimiter()
 * @see      proxy\Post::getDelimiter()
 *
 * @method   static array getAll()
 * @see      proxy\Post::getAll()
 *
 * @method   static array fetchAll()
 * @see      proxy\Post::fetchAll()
 *
 * @method   static Session clear()
 * @see      proxy\Post::clear()
 */
class Post extends AbstractProxy
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