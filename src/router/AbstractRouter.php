<?php
/**
 *  ласс AbstractRouter
 *
 * @created   by PhpStorm
 * @package   AbstractRouter.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     31.08.2015
 * @time:     6:05
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace router;

use common\Helper;
use Exception;
use exception\RouteException;
use Di\Container;


/**
 * Class AbstractRouter
 *
 * @package router
 */
class AbstractRouter implements InterfaceRouter
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

    /** путь из url*/
    protected $url;

    /** router определенный из url */
    protected $url_routes = [];

    /** controller страницы - заглушки */
    protected $controller_stub_page = 'StubPage';

    /** method страницы - заглушки */
    protected $method_stub_page = 'stubPage';

    /** string Param that sets after request url checked. */
    protected $param;
    protected $id;

    /**
     * @param \lib\Config\Config|\lib\Config\Config $config
     *
     * @throws \Exception
     */
    public function __construct(Container $config)
    {
        try
        {
            $this->site_routes = $config->get('routes');
            assert('$this->site_routes', "не найден массив 'routes'");
            $this->start();
        }
        catch(Exception $e)
        {
            throw $e;
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
     * @throws RouteException
     * @throws \Exception
     */
    public function start()
    {
        try
        {
            $url              = array_key_exists('url', $_GET) ? $_GET['url'] : 'index';
            $this->url_routes = array_values(array_filter(explode('/', $url)));
            // дл€ SEO защита от повтор€ющихс€ контроллеров /index/index/index
            if(substr_count($url, $this->url_routes[0]) > 1)
            {
                throw new RouteException('если название метода не отличаетс€ от имени контроллера то его указывать не надо');
            }

            // действительный крнтроллер и метод
            $this->searchCurrentRoure();

            if(array_key_exists(0, $this->url_routes) && $this->url_routes[0] == 'widgets')
            {
                $this->loadWidget();
            }
            else
            {
                $this->prepareParams();
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
     * вычисл€ем действительный контроллер и метод
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
            // найденный контроллер - если задан в файле routes позвол€ет мен€ть class контроллера
            $this->current_controller = $predefined_roure['controller'];
        }
        else
        {
            // или из url
            $this->current_controller = ucfirst($this->url_routes[0]);
        }
        $this->current_method = '';
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
    }

    /**
     * загрузка виджета
     */
    protected function loadWidget()
    {
        $widget_dir = $this->ucwordsKey($this->current_method);
        $this->addHelperPath(ROOT_PATH . strtolower($this->current_controller) . '/' . $widget_dir . '/');
        $widget = $this->url_routes[2];
        // вызов виджета
        $this->$widget();
        exit;
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
    public function getUrl($controller, $method, $params = null)
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
    public function gotoPage($controller, $method, $params = null)
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
            $this->prepareParams();

        }
        catch(RouteException $e)
        {
            throw $e;
        }
    }

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

    /**
     * @return mixed
     */
    public function getCurrentController()
    {
        return $this->current_controller;
    }
}