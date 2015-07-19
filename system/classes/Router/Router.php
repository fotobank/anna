<?php

/**
 * Класс Router
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
use classes\Inter\Error;

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Router
{

    /**
     * @var $param string Param that sets after request url checked.
     */
    private $param;
    /**
     * @var $id
     *  that sets after request url checked.
     */
    private $id;
    /** @var mixed
     * массив заданных роутов
     */
    protected $site_routes;
    /**
     * @var router взятый из url
     */
    public $url_routes;
    public $url_controller;
    public $url_metod;

    /**
     *
     */
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        $this->site_routes = include(SITE_PATH . 'system/classes/Router/routes.php');
    }

    /**
     * @param $route
     *
     * @throws routeException
     */
    public function set_route($route)
    {
        if (is_array($route)) {
            $this->site_routes = array_merge($this->site_routes, $route);
        } else {
            throw new routeException('$route is not array');
        }
    }

    /**
     * Mapping requested URL with specified routes in routing list.
     *
     * param array $site_routes Array list of routes from routes config file.
     */
    public function start()
    {
        try {
            $url = array_key_exists('url', $_GET) ? $_GET['url'] : '/index';
            $this->url_routes = array_values(array_filter(explode('/', $url)));
            $this->url_controller = $this->url_routes[0];

            $this->url_metod = array_key_exists(1, $this->url_routes) ? $this->url_routes[1] : false;
            $url_controller_metod = $this->url_controller . '/' . $this->url_metod;

            if (array_key_exists($this->url_controller, $this->site_routes)) {

                $this->requestOptions();

            } elseif (array_key_exists($url_controller_metod, $this->site_routes)) {

                $this->url_controller = $url_controller_metod;
                $this->requestOptions();

            } else {

                if (DEBUG_MODE) {

                    throw new routeException('controller "' . $this->url_controller . '" не задан в массиве routes', 404);

                } else {

                    $this->get404();

                }
            }

        } catch (Exception $e) {

            throw $e;
        }
    }

    /**
     * err 404
     *
     * @internal param $err
     */
    protected function get404()
    {
        try {
            if (DEBUG_MODE) {
                $controller = $this->site_routes['error404']['controller'];
                $method = $this->site_routes['error404']['method'];
                $this->prepareRoute($controller, $method);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Preparing controllerto be included. Checking is controller exists.
     * Creating new specific model instance. Creating controller instance.
     *
     * @param $controller string Controller name.
     * @param $method     string Method name.
     *
     * @throws Exception
     */
    protected function prepareRoute($controller, $method)
    {
        try {
            $this->checkControllerExists($controller);
            $this->createModelInstance($controller);
            $this->createInstance($controller, $method);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     * Checks requested URL on params and id and if exists sets to the private vars.
     *
     * @param $routes array Requested URL.
     */
    protected function prepareParams()
    {
        if (!empty($this->url_routes[2])) {
            $this->id = $this->url_routes[2];
        }
        if ((!empty($this->url_routes[3]))) {
            $this->param = $this->url_routes[3];
        }
    }

    /**
     * Checks is controller exists and inlcude it.
     *
     * @param $controller
     *
     * @throws routeException
     * @internal param $controller_path
     *
     * @internal param $controller
     *
     * @internal param string $controller_path Controller path. Used to include and controller.
     */
    protected function checkControllerExists($controller)
    {
        $controller_path = SITE_PATH . 'system' . DS . 'controllers' . DS . $controller . DS . $controller . '.php';
        if (file_exists($controller_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $controller_path;
        } else {
            throw new routeException('файл контроллера: "' . $controller_path . '" не найден', 404);
        }
    }

    /**
     * Creating new instance that required by URL.
     *
     * @param $controller string Controller name.
     * @param $method     string Method name.
     *
     * @throws routeException
     */
    protected function createInstance($controller, $method)
    {
        $controller = 'controllers\\' . $controller . '\\' . $controller;
        $instance = new $controller;

        if (method_exists($instance, $method)) {
            $reflection = new ReflectionMethod($instance, $method);
            if ($reflection->isPublic()) {
                $instance->$method($this->id, $this->param);
            } else {
                throw new routeException('метод "' . $method . '" не является публичным');
            }
        } else {
            throw new routeException('метод "' . $method . '" не найден в контроллере "' . $controller . '"', 404);
        }
    }

    /**
     * Creates instance of model by requested controller.
     *
     * @param $controller string Controller name.
     *
     * @throws routeException
     */
    protected function createModelInstance($controller)
    {

        $model_path = SITE_PATH . 'system' . DS . 'models' . DS . $controller . DS . $controller . '.php';

        if (file_exists($model_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once($model_path);
        } else {
            throw new routeException('файл модели: "' . $model_path . '" не найден', 404);
        }
    }

    /**
     * @param $url_controller
     * @param $routes
     *
     * @throws Exception
     */
    private function requestOptions()
    {
        $controller = $this->site_routes[$this->url_controller]['controller'];
        $method = '';

        if (!empty($this->site_routes[$this->url_controller]['method'])) {
            $method = $this->site_routes[$this->url_controller]['method'];
        } elseif (!empty($this->url_routes[1])) {
            $method = $this->url_routes[1];
        }
        $this->prepareParams();
        $this->prepareRoute($controller, $method);
    }
}

/**
 * Class routeException
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class routeException extends InvalidArgumentException
{

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);

        if (DEBUG_MODE) {
            throw new Exception('<b>Ошибка ' . $code . ':</b> ' . $message . '<br>');
        }
        error_log($message);
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        /** @noinspection PhpIncludeInspection */
        include(SITE_PATH . '404.php');
        exit();
    }
}