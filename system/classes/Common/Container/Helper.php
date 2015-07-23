<?php

/**
 * @namespace
 */
namespace Common\Container;

use Exception;

/**
 * Helper trait
 *
 * @link     https://github.com/bluzphp/framework/wiki/Trait-Helper
 */
trait Helper
{
    /**
     * @var array of helpers
     */
    protected $helpers = [];

    /**
     * @var array of helpers paths
     */
    protected $helpersPath = [];

    /**
     * Add helper path
     * @param string $path
     * @return self
     */
    public function addHelperPath($path)
    {
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, $this->helpersPath, true)) {
            $this->helpersPath[] = $path;
        }

        return $this;
    }

    /**
     * Set helpers path
     * @param string|array $helpersPath
     * @return self
     */
    public function setHelpersPath($helpersPath)
    {
        if (is_array($helpersPath)) {
            foreach ($helpersPath as $path) {
                $this->addHelperPath((string)$path);
            }
        } else {
            $this->addHelperPath((string)$helpersPath);
        }
        return $this;
    }

    /**
     * Call magic helper
     * @param string $method
     * @param array $args
     * @throws Exception
     * @return mixed
     */
    public function __call($method, $args)
    {
        // Setup key
        $key = static::class .':'. $method;

        // Call callable helper structure (function or class)
        if (isset($this->helpers[$key]) && is_callable($this->helpers[$key])) {
            return $this->helpers[$key](...$args);
        }

        // Try to find helper file
        foreach ($this->helpersPath as $helperPath) {
            $helperPath = realpath($helperPath . '/' . ucfirst($method) . '.php');
            if ($helperPath) {
                $helperInclude = include $helperPath;
                if (is_callable($helperInclude)) {
                    $this->helpers[$key] = $helperInclude;
                    return $this->helpers[$key](...$args);
                } else {
                    throw new Exception("Helper '$method' not found in file '$helperPath'");
                }
            }
        }
        throw new Exception("Helper '$method' not found for '" . __CLASS__ . "'");
    }
}
