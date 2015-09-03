<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

//use lib\Config\Config as Instance;
use lib\Config\ConfigException;

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
//            $instance = new Instance();
//            return $instance;
            return Di::getContainer();

        } catch(\Exception $e)
        {
            throw $e;
        }
    }
}
