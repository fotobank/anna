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
namespace classes\pattern\Proxy;

use exception\ComponentException;

/**
 * Abstract Proxy
 *
 * @package  Bluz\Proxy
 * @link     https://github.com/bluzphp/framework/wiki/Proxy
 *
 * @author   Anton Shevchuk
 * @created  26.09.2014 10:51
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
        if (!array_key_exists($class, static::$instances)) {
            static::$instances[$class] = static::initInstance();
            if (!static::$instances[$class]) {
                throw new ComponentException("Proxy class `$class` is not initialized");
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
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();
        // не нужно проверять метод на существует т.к. мы можем использовать магичесские методы класса или магические или
        // пустой класс Nil
        $reflectionMethod = new \ReflectionMethod($instance, $method);
        return $reflectionMethod->invokeArgs($instance, $args);

    }
}
