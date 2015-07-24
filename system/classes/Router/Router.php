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

use classes\pattern\Registry;
use classes\Router\InterfaceRouter;
use exception\RouteException;
use Common\Container\Options;


/**
 * Class Router
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Router implements InterfaceRouter
{

    use Options;

    private
        $param, /** string Param that sets after request url checked. */
        $id, /** that sets after request url checked. */
        $lock_page = []; // массив страниц с перенаправлением на страницу - заглушку


    /** @var mixed
     * массив заданных роутов
     */
    protected $site_routes;

    // путь из url
    protected $url;

    /* router определенный из url */
    protected $url_routes = [];

    // controller страницы - заглушки
    protected $controller_stub_page = 'StubPage';

    // method страницы - заглушки
    protected $method_stub_page = 'stubPage';

    /**
     * непосредственный маршрут
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
    }

    /**
     * @param $route
     *
     * @return mixed|void
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
     *
     * !!! название метода в url должно совпадать с именеи метода в модели
     * или должно быть прописано в routes с указанием в ключе routes текущую controller/modelm
     *
     */
    public function start()
    {
        try {
            $url = array_key_exists('url', $_GET) ? $_GET['url'] : 'index';
            $this->url_routes = array_values(array_filter(explode('/', $url)));
            // дл€ SEO защита от повтор€ющихс€ контроллеров /index/index/index
            if(substr_count($url, $this->url_routes[0]) > 1) {
                throw new RouteException('если название метода не отличаетс€ от имени контроллера то его указывать не надо');
            }
            // действительный крнтроллер и метод
            $this->searchCurrentRoure();

            $this->prepareRoute();

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
            // проверка на блокировку url страницы
            if($this->checkLockPage()) {
                // если все нормально - подготовка дополнительных параметров
                $this->prepareParams();
            }
            $this->checkControllerExists();
            $this->createInstance();

        } catch (RouteException $e) {
            throw $e;
        }
    }

    /**
     * Checks requested URL on params and id and if exists sets to the private vars.
     *
     * @internal param array $routes Requested URL.
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
            throw new RouteException('файл контроллера: "' . $controller_path . '" не найден');
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
                throw new RouteException('метод "' . $method . '" не €вл€етс€ публичным');
            }
        } else {
            throw new RouteException('метод "' . $method . '" не найден в контроллере "' .
                $controller . '"');
        }
    }

    /**
     * провер€ем страницу на блокировку
     */
    protected function checkLockPage()
    {
        try {

            $lock = Registry::call('models\Base\Base')->checkClockLockPage($this->url);

            if(false !== $lock && count($lock) > 0) {
                $this->current_controller = $lock['controller'];
                $this->current_method = $lock['method'];
                return false;
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * вычисл€ем действительный контроллер и метод
     */
    protected function searchCurrentRoure()
    {
        // $this->url_routes[0] - это url controller
        // $this->url_routes[1] - это url method
        // если в пути присутствует метод то ищеим в роутах по данному контроллеру и методу
        if(!empty($this->url_routes[1])) {
            $search_route = $this->url_routes[0] . '/' . $this->url_routes[1];
            // иначе просто по контроллеру
        } else {
            $search_route = $this->url_routes[0];
        }
        // находим однозначный url
        $this->url = $search_route;
        if(array_key_exists($search_route, $this->site_routes)) {
            // предопределеннй маршрут
            $predefined_roure = $this->site_routes[$search_route];
            // найденный контроллер - если задан в файле routesпозвол€ет мен€ть class контроллера
            $this->current_controller = $predefined_roure['controller'];
        } else {
            // или из url
            $this->current_controller = ucfirst($this->url_routes[0]);
        }
        // ищем метод
        if(!empty($predefined_roure['method'])) {
            $this->current_method = $predefined_roure['method'];
        } elseif(!empty($this->url_routes[1])) {
            // аналогично с controller из url
            $this->current_method = strtolower($this->url_routes[1]);
        } else {
            $this->current_method = '';
        }
    }

}