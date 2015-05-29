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

if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || (isset($_SESSION['logged']) && $_SESSION['logged'] == '1')) {

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(- 1); //обычно должно хватить только этой строки E_ALL
	define('DEBUG_MODE', true); // показ ошибок на монитор

} else {

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);
	define('DEBUG_MODE', false);
}

ini_set('log_errors', 1);

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

// Загружаем router
$router = new Router();
// задаем путь до папки контроллеров.
$router->setPath(SITE_PATH . 'system' . DS . 'controllers');
// запускаем маршрутизатор
$router->start();

ob_end_flush();