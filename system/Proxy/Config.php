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

use proxy\Application\Application;
use proxy\Config\Config as Instance;

/**
 * Proxy to Config
 *
 * Example of usage
 *     use Bluz\proxy\Config;
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
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        $instance->setPath(Application::getInstance()->getPath());
        $instance->setEnvironment(Application::getInstance()->getEnvironment());
        $instance->init();

        return $instance;
    }
}
