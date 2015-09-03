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

use application\Application;
use Doctrine\Common\Cache\ApcCache;
use proxy\Di;
use proxy\View;

use router\Router as MainRouter;
use DI\ContainerBuilder;
use \Interop\Container\ContainerInterface;

use lib\Config\Config;
use router\Router;
use view\View as BaseView;;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/configs/define/config.php');


// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(ROOT_PATH . DS . 'core' . DS . 'core.php');




/*$config = new Config();
$view = new BaseView();
$router = new Router($config, $view);

app()->setRouter($router);
app()->run(APP_MODE);*/




// php::di

//$container = ContainerBuilder::buildDevContainer();
//$container->set('foo', 'hello');
//$container->set('bar', new MyClass());
//$container->set('baz', DI\object('MyClass'));




//$builder = new ContainerBuilder();
//$builder->useAnnotations(true);
//$builder->setDefinitionCache(new ApcCache());
//$builder->addDefinitions('file.php');
//$builder->addDefinitions([
//                    'config' => function(){ return new Config(); },
//                    'view' => function(){ return new BaseView(); },
//                    'router' => function(ContainerInterface $c){return new MainRouter($c->get('config'), $c->get('view'));},
//                    'app' => function(){ return new Application; },
//                    Application::class => DI\object()->method('setRouter', DI\get('router')),
//                    Application::class => DI\object()->method('run')

//                         ]);
//$container = $builder->build();

$container = Di::getContainer();

$container->set('view', new BaseView());
$container->set('router', new MainRouter($container, $container->get('view')));
$container->make('router\Router');
$container->set('app', new Application());
$container->get('app')->setRouter($container->get('router\Router'));
$container->get('app')->run();






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