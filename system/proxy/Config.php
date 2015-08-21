<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use application\Application;
use config\Config as Instance;
use config\ConfigException;

/**
 * Proxy to Config
 *
 * Example of usage
 *     use proxy\Config;
 *
 *     if (!Config::getData('db')) {
 *          throw new Exception('Configuration for `db` is missed');
 *     }
 *
 * @package  Proxy
 *
 * @method   Config static Instance getInstance()
 *
 * @method   static mixed getData($key = null, $section = null)
 * @see      proxy\Config\Config::getData()
 *
 * @method   static mixed getModuleData($module, $section = null)
 * @see      proxy\Config\Config::getModuleData()
 */
class Config extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return \config\Config
     * @throws \Exception
     * @throws ConfigException
     */
    protected static function initInstance()
    {
        try
        {
            $instance = new Instance();
            $instance->setPath(Application::getInstance()->getPath());
            $instance->setEnvironment(Application::getInstance()->getEnvironment());
            $instance->init();

            return $instance;
        } catch(ConfigException $e)
        {
            throw $e;
        }
    }
}
