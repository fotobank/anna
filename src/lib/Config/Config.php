<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace lib\Config;


/**
 * Config
 *
 * @package  Config
 */
class Config implements InterfaceConfig
{
    /**
     * @var array Configuration data
     */
    protected $config;

    /**
     * @var string Path to configuration files
     */
    protected $path;

    /**
     * @var string Environment
     */
    protected $environment;


    /**
     * Load configuration
     *
     * @throws \lib\Config\ConfigException
     */
    public function __construct()
    {
        $this->setPath(ROOT_PATH);
        $this->setEnvironment(APP_MODE);
        if (!$this->path) {
            throw new ConfigException('Configuration directory is not setup');
        }

        $this->config = $this->loadFiles($this->path .'configs/default');

        if ($this->environment) {
            $customConfig = $this->loadFiles(APP_PATH . 'configs/' . $this->environment);
            $this->config = array_replace_recursive($this->config, $customConfig);
        }
    }

    /**
     * Set path to configuration files
     *
     * @param string $path
     * @throws ConfigException
     * @return void
     */
    public function setPath($path)
    {
        if (!is_dir($path)) {
            throw new ConfigException('Configuration directory is not exists');
        }
        $this->path = $path;
    }

    /**
     * Set application environment
     *
     * @param string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Load configuration file
     * @param string $path
     * @throws ConfigException
     * @return array
     */
    protected function loadFile($path)
    {
        if (!is_file($path) && !is_readable($path)) {
            throw new ConfigException('Configuration file `'.$path.'` not found');
        }

        /** @noinspection PhpIncludeInspection */
        return include $path;
    }

    /**
     * Load configuration files to array
     * @param string $path
     * @throws ConfigException
     * @return array
     */
    protected function loadFiles($path)
    {
        $config = [];

        if (!is_dir($path)) {
            throw new ConfigException('Configuration directory `'.$path.'` not found');
        }
        $iterator = new \GlobIterator($path .'/*.php',
            \FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::CURRENT_AS_PATHNAME
        );

        foreach ($iterator as $name => $file) {
            $name = substr($name, 0, -4);
            $config[$name] = $this->loadFile($file);
        }
        return $config;
    }

    /**
     * Return configuration by key
     * @api
     * @param string|null $key of config
     * @param string|null $section of config
     * @throws ConfigException
     * @return array|mixed
     */
    public function getData($key = null, $section = null)
    {
        // configuration is missed
        if (null === $this->config) {
            throw new ConfigException('System configuration is missing');
        }

        // return all configuration
        if (null === $key) {
            return $this->config;
        }

        // return part of configuration
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (isset($this->config[$key])) {
            // return section of configuration
            if (null !== $section) {
                /** @noinspection UnSafeIsSetOverArrayInspection */
                return isset($this->config[$key][$section])?$this->config[$key][$section]:null;
            } else {
                return $this->config[$key];
            }
        } else {
            return null;
        }
    }
}
