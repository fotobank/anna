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
interface InterfaceConfig
{
    /**
     * Load configuration
     *
     * @throws \lib\Config\ConfigException
     */
    public function __construct();

    /**
     * Set path to configuration files
     *
     * @param string $path
     * @throws ConfigException
     * @return void
     */
    public function setPath($path);

    /**
     * Set application environment
     *
     * @param string $environment
     * @return void
     */
    public function setEnvironment($environment);

    /**
     * Return configuration by key
     * @api
     * @param string|null $key of config
     * @param string|null $section of config
     * @throws ConfigException
     * @return array|mixed
     */
    public function getData($key = null, $section = null);
}
