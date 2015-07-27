<?php
/**
 * @namespace
 */
namespace classes\pattern\Proxy;

use classes\Router\Router as Instance;


/**
 * Proxy to Router
 *
 * Example of usage
 *     use classes\pattern\Proxy\Router;
 *
 *     Router::start()
 *
 * @package  Alex\Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static array getUrlRoutes()
 * @see      classes\pattern\Proxy\Router::getUrlRoutes()
 *
 * @method   static mixed|void setRoute($route)
 * @see      classes\pattern\Proxy\Router::setRoute()
 *
 * @method   static void start()
 * @see      classes\pattern\Proxy\Router::start()
 *
 * @author   Alex Jurii
 * @package  classes\pattern\Proxy
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
