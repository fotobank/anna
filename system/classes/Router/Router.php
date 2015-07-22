<?php

/**
 * ����� Router
 * @created   by PhpStorm
 * @package   Router.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     1:15
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


use exception\RouteException;
use Common\Container\Options;

/**
 * Class Router
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Router
{

    use Options;

    private
        $param, /** string Param that sets after request url checked. */
        $id, /** that sets after request url checked. */
        $lock_page = []; // ������ ������� � ���������������� �� �������� - ��������


    /** @var mixed
     * ������ �������� ������
     */
    protected $site_routes;
    // ���� �� url
    protected $url;
    /* router ������������ �� url */
    protected $url_routes = [];
    // controller �������� - ��������
    protected $StubPage;
    // method �������� - ��������
    protected $stubPage;

    /**
     * ���������������� �������
     * @var
     */
    public
        $current_roure = [],
        $current_controller = '',
        $current_method = '';

    /**
     *
     */
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        $this->site_routes = include(SITE_PATH . 'system/classes/Router/routes.php');
        $this->StubPage = 'StubPage';
        $this->stubPage = 'stubPage';
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
            // �������� �������� url �� 4 ��� ����������� ����������
            if(count($this->url_routes) > 4) {
                array_shift($this->url_routes);
            }
            // $this->url_routes[0] - ��� url controller
            // $this->url_routes[1] - ��� url method
            // ���� � ���� ������������ ����� �� ����� � ������ �� ������� ����������� � ������
            if(!empty($this->url_routes[1])) {
                $search_route = $this->url_routes[0] . '/' . $this->url_routes[1];
                // ����� ������ �� �����������
            } else {
                $search_route = $this->url_routes[0];
            }
            if(array_key_exists($search_route, $this->site_routes)){
                // ��������������� �������
                $predefined_roure = $this->site_routes[$search_route];
                // ��������� ���������� - ���� ����� � ����� routes��������� ������ class �����������
                $this->current_controller = $predefined_roure['controller'];
            } else {
                // ��� �� url
                $this->current_controller = ucfirst($this->url_routes[0]);
            }
            // ���� �����
            if(!empty($predefined_roure['method'])) {
                $this->current_method = $predefined_roure['method'];
            } elseif(!empty($this->url_routes[1])) {
                // ���������� � controller �� url
                $this->current_method = strtolower($this->url_routes[1]);
            } else {
                $this->current_method = '';
            }
                $this->requestOptions();

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
            $this->current_controller = $this->site_routes['404']['controller'];
            $this->current_method = $this->site_routes['404']['method'];
            $this->prepareRoute();

        } catch (RouteException $e) {
            throw $e;
        }
    }


    /**
     * Preparing controllerto be included. Checking is controller exists.
     * Creating new specific model instance. Creating controller instance.
     *
     * @throws RouteException
     */
    protected function prepareRoute()
    {
        try {
            $this->checkControllerExists();
            $this->createModelInstance();
            $this->createInstance();

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
     * @throws RouteException
     * @internal param $controller_path
     *
     * @internal param string $controller_path Controller path. Used to include and controller.
     */
    protected function checkControllerExists()
    {
        $controller_path = SITE_PATH . 'system' . DS . 'controllers' . DS . $this->current_controller .
            DS . $this->current_controller . '.php';
        if(file_exists($controller_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once $controller_path;
        } else {
            throw new RouteException('���� �����������: "' . $controller_path . '" �� ������');
        }
    }

    /**
     * Creating new instance that required by URL.
     *
     * @throws RouteException
     */
    protected function createInstance()
    {
        $controller = 'controllers\\' . $this->current_controller . '\\' . $this->current_controller;
        $method = $this->current_method;
        $instance = new $controller;

        if(method_exists($instance, $method)) {
            $reflection = new ReflectionMethod($instance, $method);
            if($reflection->isPublic()) {
                $instance->$method($this->id, $this->param);
            } else {
                throw new RouteException('����� "' . $method . '" �� �������� ���������');
            }
        } else {
            throw new RouteException('����� "' . $method . '" �� ������ � ����������� "' .
                $controller . '"');
        }
    }

    /**
     * Creates instance of model by requested controller
     *
     * @throws RouteException
     */
    protected function createModelInstance()
    {
        $model_path = SITE_PATH . 'system' . DS . 'models' . DS . $this->current_controller . DS .
            $this->current_controller . '.php';
        if(file_exists($model_path)) {
            /** @noinspection PhpIncludeInspection */
            require_once($model_path);
        } else {
            throw new RouteException('���� ������: "' . $model_path . '" �� ������');
        }
    }

    /**
     *
     */
    protected function checkLockPage()
    {
        self::db()->where('url', $this->url);
        $url_data = self::db()->getOne('lock_page');

        return false;
    }

    /**
     * @throws RouteException
     */
    private function requestOptions()
    {
        $param = $this->checkLockPage();
        if($param) {
            $this->current_controller = 'StubPage';
            $this->current_method = 'stubPage';
        } else {
            $this->prepareParams();
        }
        $this->prepareRoute();
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
            throw new RouteException($txt_err . ' ' . self::db()->getLastError());
        }
    }
}