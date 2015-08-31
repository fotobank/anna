<?php

/**
 * @namespace
 */
namespace common;

use Exception;


/**
 * Helper trait
 *
 * Dependency Injection
 */
trait Helper
{
    /**
     * Service Container
     * @var array of helpers
     */
    protected $helpers = [];

    /**
     * @var array of helpers paths
     */
    protected $helpers_path = [];

    /**
     * @var array of plugins paths
     */
    protected $plugins_path = [];

    /**
     * Add helper path
     * @param string $path
     * @return self
     */
    public function addHelperPath($path)
    {
        $path = rtrim(realpath($path), '/');
        if (false !== $path && !in_array($path, $this->helpers_path, true)) {
            $this->helpers_path[] = $path;
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
     *
     */
    public function runPlugins()
    {
        $arr_path = [];
        foreach($this->plugins_path as $path)
        {
            $arr_path = array_merge( $arr_path, glob($path.'\*.php'));
        }
        foreach($arr_path as $a)
        {
            $plugins_name = lcfirst(basename($a, '.php'));
            $this->$plugins_name();
        }
    }

    /**
     * Add plugins path
     * @param string $path
     * @return self
     */
    public function addPluginsPath($path)
    {
        $path = realpath($path);
        if (false !== $path && !in_array($path, $this->plugins_path, true)) {
            $this->plugins_path[] = $path;
        }
        $this->addHelperPath($path);
        $this->runPlugins();
        return $this;
    }

    /**
     * Set plugins path
     * @param string|array $plugins_path
     * @return self
     */
    public function setPluginsPath($plugins_path)
    {
        if (is_array($plugins_path)) {
            foreach ($plugins_path as $path) {
                $this->addPluginsPath((string)$path);
            }
        } else {
            $this->addPluginsPath((string)$plugins_path);
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
        if (array_key_exists($key, $this->helpers) && is_callable($this->helpers[$key])) {
            return call_user_func($this->helpers[$key], $args);
        }

        // Try to find helper file
        foreach ($this->helpers_path as $helperPath) {
            $helperPath = realpath($helperPath . '/' . ucfirst($method) . '.php');
            if ($helperPath)
            {
                $helperInclude = include $helperPath;
                if (is_callable($helperInclude)) {
                    $this->helpers[$key] = $helperInclude;
                    return call_user_func($this->helpers[$key], $args);
                } else {
                    throw new Exception("Helper '$method' not found in file '$helperPath'");
                }
            }
        }
        throw new Exception('Helper method "'. $method .'" not found for class "' . __CLASS__ . '"');
    }

    /**
     * Normalize key name
     * @param  string $key
     * @return string
     */
    public function ucwordsKey($key)
    {
        $option = str_replace(['_', '-'], ' ', strtolower($key));
        $option = str_replace(' ', '', ucwords($option));
        return $option;
    }
}
