<?php

namespace router;

/**
 *  ласс Router
 *
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     1:15
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use Di\Container;
use Exception;
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
     * @param \lib\Config\Config|\lib\Config\Config $config
     *
     * @param \view\View                            $view
     *
     * @throws \Exception
     */
    public function __construct(Container $config, View $view)
    {
        $this->viewer = $view;
        parent::__construct($config);
        // add and avtorun plugins
        $this->addPluginsPath(__DIR__ . '/Plugins/');
    }

    /**
     * Creating new instance that required by URL.
     */
    protected function createInstance()
    {
        $controller = $this->current_controller;
        $method = $this->current_method;
        $controller_path = 'modules\Controllers\\' . $controller . '\\' . $controller;
        $instance   = new $controller_path($this->viewer);
        // тесты
        assert('method_exists($instance, $method)', "метод '$method' не найден в контроллере '$controller_path'");
        assert('$reflection = new \ReflectionMethod($instance, $method);');
        assert('$reflection->isPublic()', "метод '$method' не €вл€етс€ публичным в контроллере '$controller_path'");

        $this->instance = $instance;
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