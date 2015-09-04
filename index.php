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

use Nette\DI\ContainerLoader;

use proxy\View;
use router\Router;
use proxy\Config;

/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/configs/define/config.php');


// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(ROOT_PATH . DS . 'core' . DS . 'core.php');



$router = new Router(Config::getInstance(), View::getInstance());
app($router)->run();




//$loader = new ContainerLoader(ROOT_PATH . 'assests/temp', false);
//$class = $loader->load('', function($compiler) {
    /** @var Nette\DI\Compiler $compiler */
//    $compiler->loadConfig(ROOT_PATH . 'configs/di/config.neon');
//});
/** @var Nette\DI\Container $container */
//$container = new $class;
//$container->getByType('application\Application')->run();




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