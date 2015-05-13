<?php
define('_SECUR', 1);
ob_start();
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 12.07.14
 * Time: 7:18
 */


if (version_compare(phpversion(), '5.4.0', '<') == true) {
	die ('PHP5.3 Only');
}
// Константы:
if (!defined("PATH_SEPARATOR")) {
	define("PATH_SEPARATOR", getenv("COMSPEC") ? ";" : ":");
}

define ('DIRSEP', DIRECTORY_SEPARATOR);
if (!defined('SITE_PATH')) {
	define ('SITE_PATH', realpath(__DIR__.DIRSEP.'..'.DIRSEP.'..'.DIRSEP).DIRSEP);
}

set_include_path(ini_get("include_path").PATH_SEPARATOR.__DIR__);
ini_set('session.auto_start', 1);
if (session_id() == '') {
	session_start();
} else {
	session_regenerate_id(true);
}

if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || (isset($_SESSION['logged']) && $_SESSION['logged'] == "1")) {

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
include(SITE_PATH.'inc/func.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'system/core/Autoloader.php');
new Core\Autoloader();

if (!is_ajax() && DEBUG_MODE) {
	$test = new Test();
}  // профилирование

new Security(); // защита

if (!defined('CODE_PAGE')) {
	define('CODE_PAGE', detect_encoding(SITE_PATH.'inc/кодировка файловой системы.codepage'));
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
/** -------------------------------------------------------------------------------------------*/

// инициализация базы
// эти значения рекомендуется не изменять - для гостевой
$GLOBALS['tbl_posts'] = "gb_posts";        // имя таблицы с сообщениями
$GLOBALS['tbl_replies'] = "gb_replies";    // имя таблицы с ответами
$GLOBALS['tbl_users'] = "gb_users";        // имя таблицы с модераторами

// $db = Db::getInstance( Db::getParam());

// mustache
/** @noinspection PhpIncludeInspection */
include(SITE_PATH.'vendor/autoload.php');
Mustache_Autoloader::register();
// инициализация шаблонизатора Mustache
$mustache = new Mustache_Engine([
									// 'template_class_prefix' => '__MyTemplates_',
									'cache'                  => (SITE_PATH.'cache/mustache'),
									'cache_file_mode'        => 0666,
									// Please, configure your umask instead of doing this :)
									'cache_lambda_templates' => true,
									'loader'                 => new Mustache_Loader_FilesystemLoader(SITE_PATH.
																									 'system/Views/Mustache/templates'),
									'partials_loader'        => new Mustache_Loader_FilesystemLoader(SITE_PATH.
																									 'system/Views/Mustache/templates/partials'),
									// 'helpers' => [ 'i18n' => function($text) {  } ],
									'escape'                 => function ($value) {
										return htmlspecialchars($value, ENT_COMPAT, 'windows-1251');
									},
									'charset'                => 'windows-1251',
									'logger'                 => new Mustache_Logger_StreamLogger(SITE_PATH.'log'),
									'strict_callables'       => true,
									'pragmas'                => [Mustache_Engine::PRAGMA_FILTERS]
								]);

// Получаем объект реестра
/*$Registry = Registry::getInstance();
$Registry['one'] = "1";
$Registry['two'] = "2";

debugHC($Registry, 'two');*/