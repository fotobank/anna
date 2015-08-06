<?php
/**
 * @namespace
 */
namespace common\Container;

/**
 * Singleton
 *
 * @package  Common
 */
trait Singleton
{
    /**
     * @var static Singleton instance
     */
    protected static $instance;

    /**
     * Get instance
     * @return static::$instance
     */
    final public static function getInstance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }

    /**
     * Disabled by access level
     */
    protected function __construct() {}

    /**
     * Disabled by access level
     */
    private function __clone() {}

    /**
     *
     */
    private function __wakeup() {}
}
