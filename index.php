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

use core\Autoloader;
use proxy\Recursive;
use proxy\Router as Router;


ob_start();

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

// Загружаем router
// Router::start();



$a = new Autoloader();
$r2 = $a->rScanDir(SITE_PATH.'classes/');

$r = Recursive::scanDir(SITE_PATH.'classes/', ['php']);
/**
 * @param $r
 *
 * @return array
 */
function converterArr($r)
{
// для autoload
    $r1 = [];
    foreach($r as $v)
    {
        foreach($v as $val)
        {
            $b        = explode('.', basename($val))[0];
            $r1[$b][] = dirname($val);
        }
    }
    return $r1;
}


$t = converterArr($r);

use proxy\Profiler;
Profiler::setIterataions(1);
Profiler::testClass('core\Autoloader',[ [], 'rScanDir', ['system/']]);
Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['system/',['php']]]);

// статичесский класс
//Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_Банкеты']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);
//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('itter',[]);
Profiler::generateResults();

ob_end_flush();