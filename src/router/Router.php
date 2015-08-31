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

use lib\Config\Config;
use Exception;


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

    /**
     * @param \lib\Config\Config|\lib\Config\Config $config
     *
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
        // add and avtorun plugins
        $this->addPluginsPath(__DIR__ . '/Plugins/');
        $this->createInstance();
    }


    /**
     * Creating new instance that required by URL.
     */
    protected function createInstance()
    {
        $controller = $this->current_controller;
        $method = $this->current_method;
        $controller_path = 'modules\Controllers\\' . $controller . '\\' . $controller;
        $instance   = new $controller_path;
        // тесты
        assert('method_exists($instance, $method)', "метод '$method' не найден в контроллере '$controller_path'");
        assert('$reflection = new \ReflectionMethod($instance, $method);');
        assert('$reflection->isPublic()', "метод '$method' не €вл€етс€ публичным в контроллере '$controller_path'");

        $this->instance = $instance;
//      $instance->$method($this->id, $this->param);
    }
}