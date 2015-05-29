<?php
/**
 * ����� ������������ ���
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

ob_start();

if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || (isset($_SESSION['logged']) && $_SESSION['logged'] == '1')) {

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(- 1); //������ ������ ������� ������ ���� ������ E_ALL
	define('DEBUG_MODE', true); // ����� ������ �� �������

} else {

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);
	define('DEBUG_MODE', false);
}

ini_set('log_errors', 1);

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

// ���������� ���� �����
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');

// ��������� router
$router = new Router();
// ������ ���� �� ����� ������������.
$router->setPath(SITE_PATH . 'system' . DS . 'controllers');
// ��������� �������������
$router->start();

ob_end_flush();