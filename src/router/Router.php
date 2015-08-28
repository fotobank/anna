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

use common\Helper;
use config\Config;
use Exception;
use exception\RouteException;
use proxy\Base as BaseModel;
use ReflectionMethod;


/**
 * Class Router
 *
 */
class Router implements InterfaceRouter
{
    use Helper;

    const DEFAULT_MODULE     = 'index';
    const DEFAULT_CONTROLLER = 'index';
    const ERROR_MODULE       = 'error';
    const ERROR_CONTROLLER   = 'index';
    /**
     * непосредственный маршрут
     *
     * @var
     */
    public
        $current_roure = [],
        $current_controller = '',
        $current_method = '';

    /** that sets after request url checked. */
    /** @var mixed
     * массив заданных роутов
     */
    protected $site_routes = [];

    // путь из url
    protected $url;

    /* router определенный из url */
    protected $url_routes = [];

    // controller страницы - заглушки
    protected $controller_stub_page = 'StubPage';

    // method страницы - заглушки
    protected $method_stub_page = 'stubPage';

    // объект контроллера
    protected $instance_controller;
    private
        $param, /** string Param that sets after request url checked. */
        $id;

    /**
     * @param \config\Config $config
     *
     * @throws \config\ConfigException
     * @throws \exception\RouteException
     */
    public function __construct(Config $config)
    {
        $this->site_routes = $config->getData('router');
        $this->start();
    }

    /**
     * Mapping requested URL with specified routes in routing list.
     *
     * param array $site_routes Array list of routes from routes config file.
     *
     * !!! название метода в url должно совпадать с именеи метода в модели
     * или должно быть прописано в routes с указанием в ключе routes текущую controller/modelm
     *
     * @throws RouteException
     * @throws \Exception
     */
    public function start()
    {
        try
        {
            $url              = array_key_exists('url', $_GET) ? $_GET['url'] : 'index';
            $this->url_routes = array_values(array_filter(explode('/', $url)));
            // для SEO защита от повторяющихся контроллеров /index/index/index
            if(substr_count($url, $this->url_routes[0]) > 1)
            {
                throw new RouteException('если название метода не отличается от имени контроллера то его указывать не надо');
            }

            // действительный крнтроллер и метод
            $this->searchCurrentRoure();

            if(isset($this->url_routes[0]) && $this->url_routes[0] == 'widgets')
            {
                $this->loadWidget();
            }
            else
            {
                $this->prepareRoute();
            }
        }
        catch(RouteException $e)
        {
            if(DEBUG_MODE)
            {
                throw $e;
            }
            else
            {
                $this->goto404();
            }
        }
    }

    /**
     * вычисляем действительный контроллер и метод
     */
    protected function searchCurrentRoure()
    {
        // $this->url_routes[0] - это url controller
        // $this->url_routes[1] - это url method
        // если в пути присутствует метод то ищеим в роутах по данному контроллеру и методу
        if(!empty($this->url_routes[1]))
        {
            $search_route = $this->url_routes[0] . '/' . $this->url_routes[1];
            // иначе просто по контроллеру
        }
        else
        {
            $search_route = $this->url_routes[0];
        }
        // находим однозначный url
        $this->url = $search_route;
        if(array_key_exists($search_route, $this->site_routes))
        {
            // предопределеннй маршрут
            $predefined_roure = $this->site_routes[$search_route];
            // найденный контроллер - если задан в файле routes позволяет менять class контроллера
            $this->current_controller = $predefined_roure['controller'];
        }
        else
        {
            // или из url
            $this->current_controller = ucfirst($this->url_routes[0]);
        }
        // ищем метод
        if(!empty($predefined_roure['method']))
        {
            $this->current_method = $predefined_roure['method'];
        }
        elseif(!empty($this->url_routes[1]))
        {
            // аналогично с controller из url
            $this->current_method = strtolower($this->url_routes[1]);
        }
        else
        {
            $this->current_method = '';
        }
    }

    /**
     * загрузка виджета
     */
    protected function loadWidget()
    {
        $widget_dir = $this->ucwordsKey($this->current_method);
        $this->addHelperPath(PATH_ROOT . strtolower($this->current_controller) . '/' . $widget_dir . '/');
        $widget = $this->url_routes[2];
        // вызов виджета
        $this->$widget();
        exit;
    }

    /**
     * Preparing controllerto be included. Checking is controller exists.
     * Creating new specific model instance. Creating controller instance.
     *
     * @throws RouteException
     */
    protected function prepareRoute()
    {
        try
        {
            // проверка на блокировку url страницы
            if($this->checkLockPage())
            {
                // если все нормально - подготовка дополнительных параметров
                $this->prepareParams();
            }
            //    $this->checkControllerExists();
            $this->createInstance();

        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * проверяем страницу на блокировку
     */
    protected function checkLockPage()
    {
        try
        {
            $lock = BaseModel::checkClockLockPage($this->url);
            if(false !== $lock && count($lock) > 0)
            {
                $this->current_controller = $lock['controller'];
                $this->current_method     = $lock['method'];

                return false;
            }

            return true;
        }
        catch(Exception $e)
        {
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
        if(!empty($this->url_routes[2]))
        {
            $this->id = $this->url_routes[2];
        }
        if((!empty($this->url_routes[3])))
        {
            $this->param = $this->url_routes[3];
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
        $method     = $this->current_method;
        $instance   = new $controller;

        if(method_exists($instance, $method))
        {
            $reflection = new ReflectionMethod($instance, $method);
            if($reflection->isPublic())
            {
                //  записываем в кеш инициализированный контроллер
                $this->instance_controller = $instance;
                //  $instance->$method($this->id, $this->param);

            }
            else
            {
                throw new RouteException('метод "' . $method . '" не является публичным');
            }
            unset($reflection, $instance);

        }
        else
        {
            throw new RouteException('метод "' . $method . '" не найден в контроллере "' . $controller . '"');
        }
    }

    /**
     * err 404
     *
     * @internal param $err
     */
    public function goto404()
    {
        try
        {
            $this->current_controller = $this->site_routes['404']['controller'];
            $this->current_method     = $this->site_routes['404']['method'];
            $this->prepareRoute();
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function getUrlRoutes()
    {
        return $this->url_routes;
    }

    /**
     * @param $route
     *
     * @return mixed|void
     * @throws RouteException
     */
    public function setRoute($route)
    {
        if(is_array($route))
        {
            $this->site_routes = array_merge($this->site_routes, $route);
        }
        else
        {
            throw new RouteException('$route is not array');
        }
    }

    /**
     * @param       $controller
     * @param       $method
     * @param array $params
     *
     * @return string
     */
    public function getUrl($controller, $method, $params = [])
    {

        $url = $controller . '/' . $method;

        if(0 === count($params))
        {
            if($controller == self::DEFAULT_CONTROLLER && $method == self::DEFAULT_MODULE)
            {
                return $controller;
            }

            return $url;
        }

        $getParams = [];
        if(0 !== count($params))
        {
            foreach($params as $key => $value)
            {
                // sub-array as GET params
                if(is_array($value))
                {
                    $getParams[$key] = $value;
                    continue;
                }
                $url .= '/' . urlencode($key) . '/' . urlencode($value);
            }
        }
        if(0 !== count($getParams))
        {
            $url .= '?' . http_build_query($getParams);
        }

        return $url;
    }

    /**
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function goto403()
    {
        try
        {
            $this->current_controller = $this->site_routes['403']['controller'];
            $this->current_method     = $this->site_routes['403']['method'];
            $this->prepareRoute();
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function stopPage()
    {
        try
        {
            $this->current_controller = $this->site_routes['stop']['controller'];
            $this->current_method     = $this->site_routes['stop']['method'];
            $this->prepareRoute();
        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @param       $controller
     * @param       $method
     * @param array $params
     *
     * @throws \Exception
     * @throws \exception\RouteException
     */
    public function gotoPage($controller, $method, $params = [])
    {
        try
        {
            $this->current_controller = $controller;
            $this->current_method     = $method;

            if(0 !== count($params))
            {
                if(!empty($params[0]))
                {
                    $this->id = $params[0];
                }
                if((!empty($params[1])))
                {
                    $this->param = $params[1];
                }
            }
            $this->prepareRoute();

        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function getInstanceController()
    {
        return $this->instance_controller;
    }

    /**
     * Checks is controller exists and inlcude it.
     *
     * @throws RouteException
     * @internal param $controller_path
     *
     * @internal param string $controller_path Controller path. Used to include and controller.
     */
    /*protected function checkControllerExists()
    {
        $controller_path = SITE_PATH . 'src' . DS . 'controllers' . DS . $this->current_controller .
            DS . $this->current_controller . '.php';
        if(file_exists($controller_path)) {
            require_once $controller_path;
        } else {
            throw new RouteException('файл контроллера: "' . $controller_path . '" не найден');
        }
    }*/

    /**
     * @return mixed
     */
    public function getCurrentMethod()
    {
        return $this->current_method;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }
}