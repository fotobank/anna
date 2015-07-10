<?php
/**
 * @created   by PhpStorm
 * @package   core.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     0:10
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

if (session_id() === '') {
	session_start();
} else {
	session_regenerate_id(true);
}

// �������� ������ ��� �������
if ($_SERVER['REMOTE_ADDR'] === '176.119.96.114') {
	$_SESSION['logged'] = '1';
}

if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || (array_key_exists('logged', $_SESSION) && $_SESSION['logged'] === '1')) {

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	define('DEBUG_MODE', true); // ����� ������ �� �������

} else {

	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	define('DEBUG_MODE', false);
}

ini_set('log_errors', 1);

header('Content-type: text/html; charset=windows-1251');

/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'system/core/Autoloader.php');
new core\Autoloader();
/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'inc/func.php');
// ��������������
if (DEBUG_MODE && !is_ajax()) {
	$test = new Test();
}
// ������
new Security();

if (!defined('CODE_PAGE')) {
	define( 'CODE_PAGE', detect_encoding(SITE_PATH . 'inc/��������� �������� �������.codepage'));
}

if (!function_exists('debugHC')) {
	/**
	 * @param        $variables
	 * @param string $group
	 */
	function debugHC($variables, $group = 'message')
		{
			if (DEBUG_MODE && is_callable($func = ['Main', 'out'])) {
				call_user_func($func, $variables, $group);
			}
		}
}

// ���� debug:
// debugHC(SITE_PATH.'classes/Mustache/templates', 'test');
// debugHC( CODE_PAGE, 'CODE_PAGE' );
// debugHC( SITE_PATH, 'SITE_PATH' );

$err = new Error();
$err->conf['logDir'] = SITE_PATH.'log';
// $err->conf['otl'] = true; // �������� ������ ���� �� 127.0.0.1
//$err->var_dump('SITE_PATH'); // ����� ����� ����������
if (!function_exists('v_dump')) {
	function v_dump()
		{
			if (DEBUG_MODE && is_callable($func = ['Error', 'var_dump'])) {
				$variables = func_get_args();
				call_user_func($func, $variables);
			}
		}
}

/** Test Begins **/
// echo $test_test; // Notice
// trigger_error('��� ����' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error