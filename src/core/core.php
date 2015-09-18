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
use lib\Security\Security;
use proxy\File;
use proxy\Location;
use proxy\Session;
use Tracy\Debugger;
use Tracy\DefaultBarPanel;
use Tracy\Dumper;


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
include(ROOT_PATH . 'core/Autoloader.php');
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
include(ROOT_PATH . 'func.php');
/** @noinspection PhpIncludeInspection */
include(ROOT_PATH . '_functions.php');

defined('CODE_PAGE') or define('CODE_PAGE', detect_encoding(SITE_PATH . 'inc/кодировка файловой системы.codepage'));

// профилирование при DEBUG_MODE
/*if(DEBUG_MODE && !is_ajax())
{
    Registry::build('Test');
}*/

// защита
new Security();


/** PRODUCTION or DEVELOPMENT or DETECT */
Debugger::enable(Debugger::DEVELOPMENT, SITE_PATH . 'src/assests/log');
/** выводить нотисы в строке
 * true - вызов Exception
 */
Debugger::$strictMode = true;
Debugger::$email      = 'aleksjurii@jmail.com';
Debugger::$maxDepth   = 5; // default: 3
Debugger::$maxLen     = 200; // default: 150
Debugger::$showLocation = true;
Debugger::$errorTemplate = ROOT_PATH . 'modules/Views/stop.php';


//Debugger::barDump($_SERVER, 'SERVER');
//Debugger::dump($_SERVER);

//Debugger::fireLog('Hello World'); // render string into Firebug console
//Debugger::fireLog($_SERVER); // or even arrays and objects
//Debugger::fireLog(new Exception('Test Exception')); // or exceptions

//$err = new Error(Config::getInstance());

//throw new exception\CommonException('Err', 301);
// echo $test_test; // Notice
// trigger_error('Это тест' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error
// $test = new TestClass();