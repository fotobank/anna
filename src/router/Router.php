<?php

namespace router;

/**
 * Класс Router
 *
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     1:15
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use Exception;
use exception\RouteException;
use lib\Config\Config;
use view\View;


/**
 * Class Router
 *
 */
class Router extends AbstractRouter
{

    /**
     * modules\Controllers\Controller
     * @var  $instance */
    protected $instance;

    protected $viewer;

    /**
     *
     * @param \lib\Config\Config $config
     * @param \view\View    $view
     *
     * @throws \Exception
     * @throws \exception\RouteException
     *
     */
    public function __construct(Config $config, View $view)
    {
        try
        {
            $this->viewer      = $view;
            $this->site_routes = $config->getData('routes');
            $this->start();
            // add and avtorun plugins
            $this->addPluginsPath(__DIR__ . '/Plugins/');
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Creating new instance that required by URL.
     */
    protected function createInstance()
    {
        try{
        $controller = $this->current_controller;
        $method = $this->current_method;
        $controller_path = 'modules\Controllers\\' . $controller . '\\' . $controller;
        $instance = new $controller_path($this->viewer);
        if(method_exists($instance, $method))
        {
            // тесты
            assert('method_exists($instance, $method)', "метод '$method' не найден в контроллере '$controller_path'");
            assert('$reflection = new \ReflectionMethod($instance, $method);');
            assert('$reflection->isPublic()', "метод '$method' не является публичным в контроллере '$controller_path'");
            $this->instance = $instance;
        }
        else
        {
            throw new RouteException("метод '$method' не найден в контроллере '$controller_path'");
        }
        }catch(RouteException $e){
            throw $e;
        }
    }

    /**
     * runInstance()
     */
    public function runInstance()
    {
        $this->createInstance();
        $method = $this->current_method;
        $this->instance->$method($this->id, $this->param);
    }
}