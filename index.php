<?php
/**
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     0:52
 * @license   MIT License: http://opensource.org/licenses/MIT
 */
use proxy\View;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/system/config/primary_config.php');

// ���������� ���� �����
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

$router = app()->init('production');
$page = View::render($router);

echo $page;


//use proxy\Profiler;
//Profiler::setIterataions(1);
//Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['system/',['php']]]);

// ������������ �����
//Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_�������']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);
//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('itter',[]);
//Profiler::generateResults();