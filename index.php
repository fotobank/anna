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
use proxy\Location;
use proxy\View;
use proxy\Router;

use classes\Router\Router as MainRouter;
use DI\ContainerBuilder;
use \Interop\Container\ContainerInterface;
use config\Config;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/config/primary_config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'src' . DS . 'core' . DS . 'core.php');

/*$router = app()->init(APP_MODE);
if($router)
{
    Router::start();
    $page = View::render();
    echo $page;
}
else
{
    Location::stopPage();
}*/


$container = ContainerBuilder::buildDevContainer();

/*$builder = new ContainerBuilder();
$builder->useAnnotations(true);
$builder->addDefinitions([
                    'Config' => new Config(),
                    'classes\Router\Router' => function(ContainerInterface $c){
                                        return new MainRouter($c->get('Config'));
                     },
                    'url_routes' => function(ContainerInterface $c){
                        return $c->get('classes\Router\Router')->getUrlRoutes();
                    }

                         ]);
$container = $builder->build();*/



$page = $container->get('view\View');
echo $page;







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