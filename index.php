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

//use classes\pattern\Proxy\Recursive;
use classes\pattern\Proxy\Router as Router;

ob_start();

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

// Загружаем router
Router::start();

//use classes\pattern\Proxy\Profiler;
//$directory = 'system/admin';

//Profiler::setIterataions(1000);
// Profiler::testClass('core\Autoloader',[ [], 'rScanDir', [$directory]]);

// $dir = new helper\Recursive();
// Profiler::testMethod($dir, 'scanDir', ['files/portfolio/03_Банкеты/']);

// Profiler::testClass('helper\Recursive',[[], 'dir', [$directory]]);

// статичесский класс
//Profiler::testClass('classes\pattern\Proxy\Recursive',[ [], 'dir', [$directory]]);

//Profiler::testClass('classes\pattern\Proxy\Recursive',[ [], 'dir', ['files/portfolio/03_Банкеты']]);
//Profiler::testClass('classes\pattern\Proxy\Recursive',[ [], 'scanDir', ['files/portfolio/03_Банкеты/']]);
//Profiler::generateResults();

// $d = Recursive::dir('system/admin');
// $r = Recursive::scanDir('system/admin/');


ob_end_flush();