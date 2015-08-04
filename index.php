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

//use proxy\Recursive;
use proxy\Router as Router;

//use proxy\Session;


ob_start();

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

// Загружаем router
// Router::start();

use proxy\Profiler;
//$directory = 'system/admin';

Profiler::setIterataions(100);
Profiler::testClass('core\Autoloader',[ [], 'rScanDir', [$directory]]);

// статичесский класс
//Profiler::testClass('proxy\Recursive',[ [], 'dir', [$directory]]);

Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_Банкеты']]);
Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['files/portfolio/03_Банкеты/']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);

//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('array_key_exists',['test1/test2/test3/test4/test5', $_SESSION]);
Profiler::generateResults();


ob_end_flush();