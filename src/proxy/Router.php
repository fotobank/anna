<?php
/**
 * @namespace
 */
namespace proxy;

use exception\RouteException;
use exception\ComponentException;
use router\Router as Instance;
use router\Route;


/**
 * Proxy to Router
 *
 * Example of usage
 *     use proxy\Router;
 *
 *     Router::start()
 *
 * @package  Alex\Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static add($pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::add($pattern, $dispatch, $callback)
 *
 * @method   static addGet($pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::addGet($pattern, $dispatch, $callback)
 *
 * @method   static addPost($pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::addPost($pattern, $dispatch, $callback)
 *
 * @method   static addPut($pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::addPut($pattern, $dispatch, $callback)
 *
 * @method   static addDelete($pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::addDelete($pattern, $dispatch, $callback)
 *
 * @method   static addMethod($method, $pattern, array $dispatch = [], $callback = null)
 * @see      proxy\Router::addMethod($method, $pattern, $dispatch, $callback)
 *
 * @method   static addRoute(Route $route, $callback = null)
 * @see      proxy\Router::addRoute(Route $route, $callback)
 *
 * @method   static addMethodRoute($method, Route $route, $callback = null)
 * @see      proxy\Router::addMethodRoute($method, $route, $callback)
 *
 * @method   static defaultCallback($callback)
 * @see      proxy\Router::defaultCallback($callback)
 *
 * @method   static mixed route($url, $noMatch = null)
 * @see      proxy\Router::route($url, $noMatch)
 *
 * @method   static mixed routeMethodFromString($method, $url, $noMatch = null)
 * @see      proxy\Router::routeMethodFromString($method, $url, $noMatch)
 *
 * @method   static mixed routeMethod($method, $url, $noMatch = null)
 * @see      proxy\Router::routeMethod($method, $url, $noMatch)
 *
 * @method   static mixed reverseRoute(array $dispatch)
 * @see      proxy\Router::reverseRoute(array $dispatch)
 *
 * @method   static mixed reverseRouteMethod($method, array $dispatch)
 * @see      proxy\Router::reverseRouteMethod($method, $dispatch)
 *
 * @method   static array getCurrentRoute()
 * @see      proxy\Router::getCurrentRoute()
 *
 * @method   static goto404()
 * @see      proxy\Router::goto404()
 *
 * @method   static goto403()
 * @see      proxy\Router::goto403()
 *
 * @method   static stopPage()
 * @see      proxy\Router::stop()
 *
 * @method   static gotoPage($controller, $method, $params = [])
 * @see      proxy\Router::gotoPage()
 *
 * @author   Alex Jurii
 * @package  Proxy
 */
class Router extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return \router\Router
     * @throws \Exception
     * @throws \exception\RouteException
     */
    protected static function initInstance()
    {
        try
        {
            return new Instance(Config::getInstance());
        }
        catch(RouteException $e)
        {
            throw $e;
        }
        catch(ComponentException $e)
        {
            throw $e;
        }
    }
}
