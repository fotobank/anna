<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Di.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     01.09.2015
 * @time:     23:36
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;


use lib\Di\Di as Instance;
use lib\Di\DiException;
use DI\ContainerBuilder;


/**
 * Class Di
 *
 * @package  Proxy
 *
 * @method   Di static Instance getInstance()
 *
 * @method   static set($name, $value)
 * @method   static mixed get($name)
 * @method   static mixed make($name, array $parameters = [])
 * @method   static bool has($name)
 * @method   static object injectOn($instance)
 * @method   static mixed call($callable, array $parameters = [])
 *
 */
class Di extends AbstractProxy
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
            $builder = new Instance();
//          $builder->setDefinitionCache(new ApcCache());

            $path = ROOT_PATH . 'configs'. DS .'default'. DS . '*.php';

            self::addConfigs($path, $builder);
            if(APP_MODE)
            {
                $path = APP_PATH . 'configs'. DS . APP_MODE . DS . '*.php';;
                self::addConfigs($path, $builder);
            }
            $container = $builder->build();

            return $container;

        }
        catch(DiException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $path
     * @param $builder
     *
     * @throws \lib\Di\DiException
     */
    private static function addConfigs($path, ContainerBuilder $builder)
    {
        if(!$path)
        {
            throw new DiException('Configuration directory `' . ROOT_PATH . '` not found');
        }
        foreach(glob($path) as $config)
        {
            $builder->addDefinitions($config);
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getContainer()
    {
        try
        {
            return static::getInstance();
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * @param $x
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke($x)
    {
        try
        {
            return static::getInstance();
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}