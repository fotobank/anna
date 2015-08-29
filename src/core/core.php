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

use core\Autoloader;
use lib\pattern\Registry;
use proxy\Config;
use proxy\Log;
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
include(SITE_PATH . 'vendor/autoload.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'src/core/Autoloader.php');
new Autoloader();

if(Session::get('logged') === true or DEBUG_MODE)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
else
{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}
if(getenv('SITE_LOG') === 'true')
{
    ini_set('log_errors', 1);
}
else
{
    ini_set('log_errors', 0);
}


/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'inc/func.php');
/** @noinspection PhpIncludeInspection */
include(PATH_ROOT . '_functions.php');

defined('CODE_PAGE') or define('CODE_PAGE', detect_encoding(SITE_PATH . 'inc/кодировка файловой системы.codepage'));

// профилирование при DEBUG_MODE
if(DEBUG_MODE && !is_ajax())
{
    Registry::build('Test');
}

// защита
new Security();

$err                 = new lib\Inter\Error(Config::getInstance());
$err->conf['logDir'] = SITE_PATH . 'log';
$err->conf['otl']    = true; // включить запись лога на 127.0.0.1
//$err->var_dump($_SERVER, '$_SERVER'); // вывод дампа переменных
if(!function_exists('v_dump'))
{
    function v_dump()
    {
        if(DEBUG_MODE && is_callable($func = ['lib\Inter\Error', 'var_dump']))
        {
            $variables = func_get_args();
//            call_user_func($func, $variables);
            $dump = new lib\Inter\Error(Config::getInstance());
            $dump->var_dump($variables);
            unset($dump);
        }
    }
}
//v_dump($_SERVER);
//throw new exception\CommonException('Err', 301);
// echo $test_test; // Notice
// trigger_error('Это тест' , E_USER_ERROR ); // User_Error
 throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error
// $test = new TestClass();