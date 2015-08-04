<?php
/**
 * @namespace
 */
namespace proxy;

use classes\Router\Router as Instance;


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
 * @method   static array getUrlRoutes()
 * @see      proxy\Router::getUrlRoutes()
 *
 * @method   static mixed|void setRoute($route)
 * @see      proxy\Router::setRoute()
 *
 * @method   static void start()
 * @see      proxy\Router::start()
 *
 * @author   Alex Jurii
 * @package  Proxy
 */
class Router extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
    }

}
