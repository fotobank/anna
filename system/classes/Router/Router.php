<?php

/**
 *  ласс Router
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


use exception\RouteException;


/**
 * Class Router
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Router
{

    private
        $param, /** string Param that sets after request url checked. */
        $id, /** that sets after request url checked. */
        $lock_page = []; // перенаправление на страницу - заглушку


    /** @var mixed
     * массив заданных роутов
     */
    protected $site_routes;

    /**
     * @var router вз€тый из url
     */
    public $url_routes = [];

    public $url;

    public $url_controller = '';

    public $url_metod = '';

    /**
     * непосредственный маршрут
     * @var
     */
    public $current_roure = [];

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
     * @throws RouteException
     */
    public function setRoute($route)
    {
        if(is_array($route)) {
            $this->site_routes = array_merge($this->site_routes, $route);
        } else {
            throw new RouteException('$route is not array');
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
            $this->url = array_key_exists('url', $_GET) ? $_GET['url'] : '/index';
            $this->url_routes = array_values(array_filter(explode('/', $this->url)));
            $url_controller = $this->url_routes[0];
            $this->url_metod = array_key_exists(1, $this->url_routes) ? $this->url_routes[1] : false;
            // провер€ем присутствует ли в пути название метода
            $url_controller_metod = ($this->url_metod) ? $url_controller . '/' . $this->url_metod : false;
            // собираем возможые контроллеры в массив и чистим пустые значени€
            // затем подготавливаем дл€ сравнени€ по ключам (мен€ем местами ключ => значение)
            $controllers = array_flip(array_filter(compact('url_controller', 'url_controller_metod')));
            // сравнение по ключам
            $this->current_roure = array_intersect_key(($this->site_routes), $controllers);
            // разбивка массива дл€ более удобной работы с ним
            $this->current_roure = each( $this->current_roure );
            // найденный контроллер
            $this->url_controller = $this->current_roure['key'];

            if($this->url_controller) {
                $this->requestOptions();
            } else {
                throw new RouteException('controller "' . $this->url_controller . '" не задан в массиве routes');
            }
        } catch (RouteException $e) {
            if(DEBUG_MODE) {
                throw $e;
            } else {
                $this->get404();
            }
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
            $controller = $this->site_routes['404']['controller'];
            $method = $this->site_routes['404']['method'];
            $this->prepareRoute($controller, $method);

        } catch (RouteException $e) {
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
     * @throws RouteException
     */
    protected function prepareRoute($controller, $method)
    {
        try {
            $this->checkControllerExists($controller);
            $this->createModelInstance($controller);
            $this->createInstance($controller, $method);

        } catch (RouteException $e) {
            throw $e;
        }
    }

    /**
     * Checks requested URL on params and id and if exists sets to the private vars.
     *
     * @param $routes array Requested URL.
     */
    protected function prepareParams()
    {
        if(!empty($this->url_routes[2])) {
            $this->id = $this->url_routes[2];
        }
        if((!empty($this->url_routes[3]))) {
            $this->param = $this->url_routes[3];
        }
    }

    /**
     * Checks is controller exists and inlcude it.
     *
     * @param $controller
     *
     * @throws RouteException
     * @internal param $controller_path
     *
     * @internal param $controller
     *
     * @internal param string $controller_path Controller path. Used to include and controller.
     */
    protected function checkControllerExists($controller)
    {
        $controller_path = SITE_PATH . 'system' . DS . 'controllers' . DS . $controller . DS . $controller . '.php';
        if(file_exists($controller_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $controller_path;
        } else {
            throw new RouteException('файл контроллера: "' . $controller_path . '" не найден');
        }
    }

    /**
     * Creating new instance that required by URL.
     *
     * @param $controller string Controller name.
     * @param $method     string Method name.
     *
     * @throws RouteException
     */
    protected function createInstance($controller, $method)
    {
        $controller = 'controllers\\' . $controller . '\\' . $controller;
        $instance = new $controller;

        if(method_exists($instance, $method)) {
            $reflection = new ReflectionMethod($instance, $method);
            if($reflection->isPublic()) {
                $instance->$method($this->id, $this->param);
            } else {
                throw new RouteException('метод "' . $method . '" не €вл€етс€ публичным');
            }
        } else {
            throw new RouteException('метод "' . $method . '" не найден в контроллере "' . $controller . '"');
        }
    }

    /**
     * Creates instance of model by requested controller.
     *
     * @param $controller string Controller name.
     *
     * @throws RouteException
     */
    protected function createModelInstance($controller)
    {
        $model_path = SITE_PATH . 'system' . DS . 'models' . DS . $controller . DS . $controller . '.php';
        if(file_exists($model_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once($model_path);
        } else {
            throw new RouteException('файл модели: "' . $model_path . '" не найден');
        }
    }

    /**
     *
     */
    protected function checkLockPage()
    {
        self::db()->where('url', $this->url);
        $url_data = self::db()->getOne('lock_page');

        return true;
    }

    /**
     * @throws RouteException
     */
    private function requestOptions()
    {
        $param = $this->checkLockPage();
        if(!$param) {
            $controller = 'StubPage';
            $method = 'stubPage';
        } else {
            $controller = $this->current_roure['value']['controller'];
            if(!empty($this->current_roure['value']['method'])) {
                $method = $this->current_roure['value']['method'];
            } elseif(!empty($this->url_routes[1])) {
                $method = $this->url_routes[1];
            } else {
                $method = '';
            }
            $this->prepareParams();
        }
        $this->prepareRoute($controller, $method);
    }

    /**
     * @return object
     */
    protected static function db()
    {
        return Db::getInstance(Db::getParam());
    }

    /**
     * @param $txt_err
     *
     * @throws \Exception
     */
    public function ifError($txt_err)
    {
        if(self::db()->getLastError() !== '&nbsp;&nbsp;') {
            throw new \Exception($txt_err . ' ' . self::db()->getLastError());
        }
    }
}