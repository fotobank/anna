<?php
/**
 * @created   by PhpStorm
 * @package   core.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     0:10
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use classes\pattern\Registry;
use core\Autoloader;
use proxy\Server;
use proxy\Session;


if(session_id() === '')
{
    session_start();
}
else
{
    session_regenerate_id(true);
}

/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/core/Autoloader.php');
new Autoloader();

// включить админа для отладки
if(Server::get('REMOTE_ADDR') == ('85.238.118.31' || '127.0.0.1'))
{
    Session::set('logged', true);
}
if(Session::get('logged') === true)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define('DEBUG_MODE', true); // показ ошибок на монитор
}
else
{

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    define('DEBUG_MODE', false);
}

ini_set('log_errors', 1);

header('Content-type: text/html; charset=windows-1251');


/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'inc/func.php');
// профилирование
if(DEBUG_MODE && ! is_ajax())
{
    Registry::build('Test');
}
// защита
new Security();

if( ! defined('CODE_PAGE'))
{
    define(
    'CODE_PAGE', detect_encoding(
        SITE_PATH . 'inc/кодировка файловой системы.codepage'
    )
    );
}

if( ! function_exists('debugHC'))
{
    /**
     * @param        $variables
     * @param string $group
     */
    function debugHC($variables, $group = 'message')
    {
        if(DEBUG_MODE && is_callable($func = ['Main', 'out']))
        {
            call_user_func($func, $variables, $group);
        }
    }
}

// демо debug:
// debugHC(SITE_PATH.'classes/Mustache/templates', 'test');
// debugHC( CODE_PAGE, 'CODE_PAGE' );
// debugHC( SITE_PATH, 'SITE_PATH' );

$err                 = new classes\Inter\Error();
$err->conf['logDir'] = SITE_PATH . 'log';
// $err->conf['otl'] = true; // включить запись лога на 127.0.0.1
//$err->var_dump('SITE_PATH'); // вывод дампа переменных
if( ! function_exists('v_dump'))
{
    function v_dump()
    {
        if(DEBUG_MODE && is_callable($func = ['Error', 'var_dump']))
        {
            $variables = func_get_args();
            call_user_func($func, $variables);
        }
    }
}

/** Test Begins **/
// echo $test_test; // Notice
// trigger_error('Это тест' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error
//$test = new TestClass();