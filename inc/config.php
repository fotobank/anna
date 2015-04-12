<?php
define( '_SECUR', 1 );
ob_start();
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 12.07.14
 * Time: 7:18
 */
if ( $_SERVER['REMOTE_ADDR'] === '188.115.142.130' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ||
	isset( $_SESSION['logged'] ) && $_SESSION['logged'] == "1" ) {

	ini_set( 'display_errors', 1 );
	ini_set( 'display_startup_errors', 1 );
	error_reporting( - 1 ); /* обычно должно хватить только этой строки E_ALL */
	define( 'DEBUG_MODE', true ); // показ ошибок на монитор
	require_once( __DIR__ . '/../classes/Ubench/Test.php' );  // профилирование
	$bench = new Ubench_Test;
	$bench->start();
} else {

	ini_set( 'display_errors', 0 );
	ini_set( 'display_startup_errors', 0 );
	error_reporting( E_ALL );
	define( 'DEBUG_MODE', false );
}
ini_set( 'log_errors', 1 );

require_once( __DIR__ . '/../classes/Alex/Security.php' );

if ( ! defined( "PATH_SEPARATOR" ) )
	define( "PATH_SEPARATOR", getenv( "COMSPEC" ) ? ";" : ":" );
set_include_path( ini_get( "include_path" ) . PATH_SEPARATOR . __DIR__ );
ini_set( 'session.auto_start', 1 );
if(session_id() == '')
	session_start();
else session_regenerate_id(true);


if ( ! defined( 'SITE_PATH' ) ) {
	define( 'SITE_PATH', realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR );
}
include (SITE_PATH.'./classes/autoload.php');
autoload::getInstance();

// mustache
// include ('./vendor/autoload.php');
// инициализация шаблонизатора Mustache
/*$mustache = new Mustache_Engine( [
	// 'template_class_prefix' => '__MyTemplates_',
	'cache' => (SITE_PATH.'cache/mustache'),
	'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
	'cache_lambda_templates' => true,
	'loader' => new Mustache_Loader_FilesystemLoader( SITE_PATH.'classes/Mustache/templates'),
	'partials_loader' => new Mustache_Loader_FilesystemLoader(SITE_PATH.'classes/Mustache/templates/partials'),
	// 'helpers' => [ 'i18n' => function($text) {  } ],
	'escape' => function($value) { return htmlspecialchars($value, ENT_COMPAT, 'windows-1251'); },
	'charset' => 'windows-1251',
	'logger' => new Mustache_Logger_StreamLogger(SITE_PATH.'log'),
	'strict_callables' => true,
	'pragmas' => [Mustache_Engine::PRAGMA_FILTERS],
] );*/




if ( ! function_exists( 'debugHC' ) ) {
	/**
	 * @param        $v
	 * @param string $group
	 */
	function debugHC( $v, $group = "message" ) {
		if ( DEBUG_MODE && is_callable( $f = [ 'Debug_HackerConsole_Main', 'out' ] ) ) {
			call_user_func( $f, $v, $group );
		}
	}
}

// демо debug:
// debugHC(SITE_PATH.'classes/Mustache/templates', 'test');
Inter_Error::init();
Inter_Error::$conf['logDir'] = SITE_PATH . 'log';
Inter_Error::$conf['otl'] = true; // включить запись лога на 127.0.0.1
// Inter_Error::var_dump('Test'); // вывод дампа переменных
if ( ! function_exists( 'v_dump' ) ) {
	function v_dump() {
		if ( DEBUG_MODE && is_callable( $func = [ 'Inter_Error', 'var_dump' ] ) ) {
			$variables = func_get_args();
			call_user_func( $func, $variables );
		}
	}
}
/** Test Begins **/
// echo $test_test; // Notice
// trigger_error('Это тест' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error.
/** -------------------------------------------------------------------------------------------*/

// эти значения рекомендуется не изменять
$GLOBALS['tbl_posts']	=	"gb_posts";		// имя таблицы с сообщениями
$GLOBALS['tbl_replies']	=	"gb_replies";	// имя таблицы с ответами
$GLOBALS['tbl_users']	=	"gb_users";		// имя таблицы с модераторами

require_once( __DIR__ . '/func.php' );
// инициализация базы
$db = Mysqli_Db::getInstance( Mysqli_Db::get_param());