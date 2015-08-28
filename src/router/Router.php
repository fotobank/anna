<?php

namespace router;

/**
 * ����� Router
 *
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
     * ���������������� �������
     *
     * @var
     */
    public
        $current_roure = [],
        $current_controller = '',
        $current_method = '';

    /** that sets after request url checked. */
    /** @var mixed
     * ������ �������� ������
     */
    protected $site_routes = [];

    // ���� �� url
    protected $url;

    /* router ������������ �� url */
    protected $url_routes = [];

    // controller �������� - ��������
    protected $controller_stub_page = 'StubPage';

    // method �������� - ��������
    protected $method_stub_page = 'stubPage';

    // ������ �����������
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
     * !!! �������� ������ � url ������ ��������� � ������ ������ � ������
     * ��� ������ ���� ��������� � routes � ��������� � ����� routes ������� controller/modelm
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
            // ��� SEO ������ �� ������������� ������������ /index/index/index
            if(substr_count($url, $this->url_routes[0]) > 1)
            {
                throw new RouteException('���� �������� ������ �� ���������� �� ����� ����������� �� ��� ��������� �� ����');
            }

            // �������������� ���������� � �����
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
     * ��������� �������������� ���������� � �����
     */
    protected function searchCurrentRoure()
    {
        // $this->url_routes[0] - ��� url controller
        // $this->url_routes[1] - ��� url method
        // ���� � ���� ������������ ����� �� ����� � ������ �� ������� ����������� � ������
        if(!empty($this->url_routes[1]))
        {
            $search_route = $this->url_routes[0] . '/' . $this->url_routes[1];
            // ����� ������ �� �����������
        }
        else
        {
            $search_route = $this->url_routes[0];
        }
        // ������� ����������� url
        $this->url = $search_route;
        if(array_key_exists($search_route, $this->site_routes))
        {
            // ��������������� �������
            $predefined_roure = $this->site_routes[$search_route];
            // ��������� ���������� - ���� ����� � ����� routes ��������� ������ class �����������
            $this->current_controller = $predefined_roure['controller'];
        }
        else
        {
            // ��� �� url
            $this->current_controller = ucfirst($this->url_routes[0]);
        }
        // ���� �����
        if(!empty($predefined_roure['method']))
        {
            $this->current_method = $predefined_roure['method'];
        }
        elseif(!empty($this->url_routes[1]))
        {
            // ���������� � controller �� url
            $this->current_method = strtolower($this->url_routes[1]);
        }
        else
        {
            $this->current_method = '';
        }
    }

    /**
     * �������� �������
     */
    protected function loadWidget()
    {
        $widget_dir = $this->ucwordsKey($this->current_method);
        $this->addHelperPath(PATH_ROOT . strtolower($this->current_controller) . '/' . $widget_dir . '/');
        $widget = $this->url_routes[2];
        // ����� �������
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
            // �������� �� ���������� url ��������
            if($this->checkLockPage())
            {
                // ���� ��� ��������� - ���������� �������������� ����������
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
     * ��������� �������� �� ����������
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
                //  ���������� � ��� ������������������ ����������
                $this->instance_controller = $instance;
                //  $instance->$method($this->id, $this->param);

            }
            else
            {
                throw new RouteException('����� "' . $method . '" �� �������� ���������');
            }
            unset($reflection, $instance);

        }
        else
        {
            throw new RouteException('����� "' . $method . '" �� ������ � ����������� "' . $controller . '"');
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
            throw new RouteException('���� �����������: "' . $controller_path . '" �� ������');
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