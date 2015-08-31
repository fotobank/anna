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

use proxy\View;

use router\Router as MainRouter;
use DI\ContainerBuilder;
use \Interop\Container\ContainerInterface;
use lib\Config\Config;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/configs/define/config.php');


// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(ROOT_PATH . DS . 'core' . DS . 'core.php');

app()->init(APP_MODE);


// proxy
//echo View::render();



// php::di
//$container = ContainerBuilder::buildDevContainer();

$builder = new ContainerBuilder();
$builder->useAnnotations(false);
$builder->addDefinitions([
                    'Config' => new Config(),
                    'router\Router' => function(ContainerInterface $c){
                                        return new MainRouter($c->get('Config'));
                     },
                    'url_routes' => function(ContainerInterface $c){
                        return $c->get('router\Router')->getUrlRoutes();
                    },
                    'environment' => 'production',


                         ]);
$container = $builder->build();

echo $container->get('view\View');




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