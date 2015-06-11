<?php
/**
 * Класс предназначен для
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

ob_start();

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

/** @noinspection PhpIncludeInspection */
$site_routes = include(SITE_PATH.'system/classes/Router/routes.php');
// Загружаем router
$router = new Router();
// задаем путь до папки контроллеров.
//$router->setPath(SITE_PATH . 'system' . DS . 'controllers');
// запускаем маршрутизатор
$router->start($site_routes);


//include(SITE_PATH.'system/classes/Router/example.php');


//$router = Router::getInstance();
// setStaticRoute - param1 url string, param 2 module/controller/action
//$router->setStaticRoute('_404', 'error/index/notfound');

//setWileRoute - param1 url required (more can be after), param2 module, controller,action
//$router->setWildRoute('news/:id', 'cms/news/list');
//route above would match as follows:
//url://dom.ext/news/1 - map to cms - news::list id=1
//url://dom.ext/news/1/page/2 - map to cms - news::list id=1 page=2

// $http_url = isset($_GET['route'])?$_GET['route']:'/';

//$http_url = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
//$url = cp1251(urldecode(parse_url($http_url, PHP_URL_PATH)));
//$url = isset($_GET['url'])?$_GET['url']:'/';


/** @noinspection PhpIncludeInspection */
//$routes = include (SITE_PATH.'system/classes/Router/routes.php');

//$router = new Router($routes);


//$result = $router->match('GET', $url);


ob_end_flush();