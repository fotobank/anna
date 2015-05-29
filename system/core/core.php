<?php
/**
 * Класс предназначен для 
 * @created   by PhpStorm
 * @package   core.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     0:10
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

if (session_id() == '') {
	session_start();
} else {
	session_regenerate_id(true);
}

/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'inc/func.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'system/core/Autoloader.php');
new core\Autoloader();
// профилирование
if (!is_ajax() && DEBUG_MODE) {
	$test = new Test();
}
// защита
new Security();

if (!defined('CODE_PAGE')) {
	define( 'CODE_PAGE', detect_encoding(SITE_PATH . 'inc/кодировка файловой системы.codepage'));
}

if (!function_exists('debugHC')) {
	/**
	 * @param        $variables
	 * @param string $group
	 */
	function debugHC($variables, $group = "message")
		{
			if (DEBUG_MODE && is_callable($func = ['Main', 'out'])) {
				call_user_func($func, $variables, $group);
			}
		}
}

// демо debug:
// debugHC(SITE_PATH.'classes/Mustache/templates', 'test');
// debugHC( CODE_PAGE, 'CODE_PAGE' );
// debugHC( SITE_PATH, 'SITE_PATH' );

Error::init();
Error::$conf['logDir'] = SITE_PATH.'log';
// Error::$conf['otl'] = true; // включить запись лога на 127.0.0.1
// Error::var_dump('Test'); // вывод дампа переменных
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
// trigger_error('Это тест' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error.
