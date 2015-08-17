<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace proxy;

use exception\CommonException;
use exception\ComponentException;

/**
 * Abstract Proxy
 */
abstract class AbstractProxy
{
    /**
     * @var array Instances of classes
     */
    protected static $instances = [];

    /**
     * Init class instance
     *
     * @abstract
     * @internal
     * @throws ComponentException
     * @return mixed
     */
    protected static function initInstance()
    {
        throw new ComponentException(
            'Realization of method `initInstance()` is required for class `'. static::class .'`'
        );
    }

    /**
     * Get class instance
     *
     * @throws ComponentException
     * @return mixed
     */
    public static function getInstance()
    {
        $class = static::class;
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset(static::$instances[$class]))
        {
            static::$instances[$class] = static::initInstance();
            if (!static::$instances[$class])
            {
                throw new ComponentException("Прокси класс `$class` не инициализируется");
            }
        }
        return static::$instances[$class];
    }

    /**
     * Set or replace instance
     *
     * @param  mixed $instance
     * @return void
     */
    public static function setInstance($instance)
    {
        static::$instances[static::class] = $instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @internal
     *
     * @param  string $method
     * @param  array  $args
     *
     * @return mixed
     * @throws \Exception
     * @throws \exception\CommonException
     */
    public static function __callStatic($method, $args)
    {
        try
        {
            $instance = static::getInstance();
            // $reflectionMethod = new \ReflectionMethod($instance, $method);
            // return $reflectionMethod->invokeArgs($instance, $args);

            // если вызван метод с неопределенным количеством аргументов
            if(isset($args[0]) && is_array($args[0]))
            {
                $args = array_shift($args);
            }
            // не нужно проверять метод на существует т.к. мы можем использовать магичесские методы класса
            // или магические или пустой класс
            return call_user_func_array([$instance, $method], $args);
        }
        catch(CommonException $e)
        {
            throw $e;
        }
    }
}
