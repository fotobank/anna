<?php
/**
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     0:52
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use modules\Controllers\Index\Index;
use Nette\DI\ContainerLoader;

use proxy\Config;
use proxy\View;
use proxy\Router;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/configs/define/config.php');


// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(ROOT_PATH . DS . 'core' . DS . 'core.php');


//$router = new Router(Config::getInstance(), View::getInstance());
//app($router)->run();


//$loader = new ContainerLoader(ROOT_PATH . 'assests/temp', true);
//$class = $loader->load('', function($compiler) {
/** @var Nette\DI\Compiler $compiler */
//    $compiler->loadConfig(ROOT_PATH . 'configs/di/config.neon');
//});
/** @var Nette\DI\Container $container */
//$container = new $class;
//$container->getByType('application\Application')->run();


use router\Route;

require_once ROOT_PATH . 'router/Router.php';


if(!function_exists('dispatch'))
{
    /**
     * @param \router\Route $route
     *
     * @throws \exception\ComponentException
     */
    function dispatch(Route $route)
    {
        $controller_path = 'modules\Controllers\\' . $route->controller . '\\' . $route->controller;
        $model_path = 'modules\Models\\' . $route->controller . '\\' . $route->controller;
        $view_path = 'modules\Views\\' . $route->controller . '\\' . $route->controller;
        $model = new $model_path(Config::getInstance());
		$view = new $view_path;
        $controller = new $controller_path($model, $view);
        $controller->{$route->action}();
    }
}

if(!function_exists('carousel'))
{
    /**
     * @param \router\Route $route
     *
     * @throws \exception\ComponentException
     */
    function carousel(Route $route)
    {
        $name            = $route->dispatchValues();
        $controller_path = 'modules\Controllers\\' . $name['controller'] . '\\' . $name['controller'];
        $controller      = new $controller_path();
        $controller->{$name['action']}($name['dir'], $name['file']);
    }
}


if(!function_exists('widgets'))
{
    /**
     * @param \router\Route $route
     *
     * @throws \exception\ComponentException
     */
    function widgets(Route $route)
    {
        $name        = $route->dispatchValues();
        $method = str_replace(['_', '-'], ' ', strtolower($name['method']));
        $method = str_replace(' ', '', ucwords($method));
        $widget_path = ROOT_PATH . strtolower($name['controller']) . DS . $method . DS . ucwords($name['action']). '.php';
        // вызов виджета
        $widget = include $widget_path;
        /** @var string $widget */
        $widget();
        exit;
    }
}


//Router::add('/', ['controller' => 'Index', 'action' => 'index']);
//Router::add('/index', ['controller' => 'Index', 'action' => 'index']);
//Router::add('/carousel/:action/:dir/:file', ['controller' => 'Carousel'], 'carousel');

Router::add('/index/bar', [], function ()
{
    header('Location: /bar/baz');
    exit;
});

Router::add('/widgets/:method/:action', ['controller' => 'widgets' ], 'widgets');

Router::defaultCallback('dispatch');

Router::add('/*', [], 'Error::handle404');
// $url = isset($_GET['url'])?$_GET['url']:'/';
Router::route($_GET['url']);



//use proxy\Profiler;
//Profiler::setIterataions(1);
//Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['src/',['php']]]);

// статичесский класс
//Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_Банкеты']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);
//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('itter',[]);
//Profiler::generateResults();