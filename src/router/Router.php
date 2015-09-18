<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     10.09.2015
 * @time:     16:39
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace router;


use exception\RouteException;
use lib\Config\ConfigException;
use lib\Config\InterfaceConfig;


/**
 * Class Router
 *
 * @package router
 */
class Router extends AbstractRouter
{

    /**
     * @param \lib\Config\InterfaceConfig $config
     * @param \router\RouteFactory|null   $routeFactory
     *
     * @throws \Exception
     * @throws \lib\Config\ConfigException
     */
    public function __construct(InterfaceConfig $config, RouteFactory $routeFactory = null)
    {

        try
        {
            parent::__construct($routeFactory);

            $config_routes = $config->getData('routes');
            $this->addRoures($config_routes);
        }
        catch(ConfigException $e)
        {
            throw $e;
        }

    }


    /**
     * err 404
     *
     * @internal param $err
     */
    public function goto404()
    {
        try
        {
            $this->current_controller = $this->site_routes['404']['controller'];
            $this->current_method     = $this->site_routes['404']['method'];
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function goto403()
    {
        try
        {
            $this->current_controller = $this->site_routes['403']['controller'];
            $this->current_method     = $this->site_routes['403']['method'];
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function stopPage()
    {
        try
        {
            $this->current_controller = $this->site_routes['stop']['controller'];
            $this->current_method     = $this->site_routes['stop']['method'];
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @param       $controller
     * @param       $method
     * @param array $params
     *
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function gotoPage($controller, $method, $params = null)
    {
        try
        {
            $this->current_controller = $controller;
            $this->current_method     = $method;

            if(0 !== count($params))
            {
                if(!empty($params[0]))
                {
                    $this->id = $params[0];
                }
                if((!empty($params[1])))
                {
                    $this->param = $params[1];
                }
            }
            $this->prepareParams();

        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $config_routes
     *
     * @throws \Exception
     */
    protected function addRoures($config_routes)
    {
        if(is_array($config_routes))
        {
            foreach($config_routes as $pattern => $route)
            {
                $dispatch = array_key_exists('dispatch', $route) ? $route['dispatch'] : [];
                $callback = array_key_exists('callback', $route) ? $route['callback'] : null;
                $this->add($pattern, $dispatch, $callback);
            }
        }
    }
}