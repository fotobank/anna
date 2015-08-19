<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace common\Container;

/**
 * Implements magic access to container
 *
 * @package  Common
 *
 * @method void setContainer($key, $value)
 * @method mixed getContainer($key)
 * @method bool containsContainer($key)
 * @method void deleteContainer($key)
 *
 */
trait MagicAccess
{
    /**
     * Magic alias for set() regular method
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setContainer($key, $value);
    }

    /**
     * Magic alias for get() regular method
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getContainer($key);
    }

    /**
     * Magic alias for contains() regular method
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->containsContainer($key);
    }

    /**
     * Magic alias for delete() regular method
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->deleteContainer($key);
    }
}
