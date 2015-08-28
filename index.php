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

use router\Router as MainRouter;
use DI\ContainerBuilder;
use \Interop\Container\ContainerInterface;
use config\Config;


/** @noinspection PhpIncludeInspection */
include(__DIR__ . '/src/config/primary_config.php');

// ���������� ���� �����
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'src' . DS . 'core' . DS . 'core.php');

app()->init(APP_MODE);


// proxy
//echo View::render();



// php::di
//$container = ContainerBuilder::buildDevContainer();

$builder = new ContainerBuilder();
$builder->useAnnotations(false);
debug($_SERVER['PERCORSO_GLOBALS']);
$builder->addDefinitions([
                    'Config' => new Config(),
                    'router\Router' => function(ContainerInterface $c){
                                        return new MainRouter($c->get('Config'));
                     },
                    'url_routes' => function(ContainerInterface $c){
                        return $c->get('router\Router')->getUrlRoutes();
                    }

                         ]);
$container = $builder->build();

echo $container->get('views\View\View');



//use proxy\Profiler;
//Profiler::setIterataions(1);
//Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['src/',['php']]]);

// ������������ �����
//Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_�������']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);
//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('itter',[]);
//Profiler::generateResults();