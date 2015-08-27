<?php
/**
 * @namespace
 */
namespace common\Container;

/**
 * Container of data for object
 *
 * @package  Common
 */
trait Container
{
    /**
     * @var array Container of elements
     */
    protected $container = [];

    /**
     * Set key/value pair
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setContainer($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * Get value by key
     * @param string $key
     * @return mixed
     */
    protected function getContainer($key)
    {
        if ($this->containsContainer($key)) {
            return $this->container[$key];
        } else {
            return null;
        }
    }

    /**
     * Check contains key in container
     * @param string $key
     * @return bool
     */
    protected function containsContainer($key)
    {
        return array_key_exists($key, $this->container);
    }

    /**
     * Delete value by key
     * @param string $key
     * @return void
     */
    protected function deleteContainer($key)
    {
        unset($this->container[$key]);
    }

    /**
     * Sets all data in the row from an array
     * @param  array $data
     * @return self
     */
    public function setFromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $this->container[$key] = $value;
        }
        return $this;
    }

    /**
     * Returns the column/value data as an array
     * @return array
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * Reset container array
     * @return self
     */
    public function resetArray()
    {
        foreach ($this->container as &$value) {
            $value = null;
        }
        return $this;
    }
}
