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
	error_reporting( - 1 ); /* ������ ������ ������� ������ ���� ������ E_ALL */
	define( 'DEBUG_MODE', true ); // ����� ������ �� �������
	require_once( __DIR__ . '/../classes/Ubench/Test.php' );  // ��������������
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
include_once( __DIR__ . '/../classes/autoload.php' );
autoload::getInstance();

if ( ! function_exists( 'debugHC' ) ) {
	function debugHC( $v, $group = "message" ) {
		if ( DEBUG_MODE && is_callable( $f = array( 'Debug_HackerConsole_Main', 'out' ) ) ) {
			call_user_func( $f, $v, $group );
		}
	}
}

// debugHC(SITE_PATH, 'test');
Inter_Error::init();
Inter_Error::$conf['logDir'] = SITE_PATH . 'log';
Inter_Error::$conf['otl'] = true; // �������� ������ ���� �� 127.0.0.1
// Inter_Error::var_dump('Test'); // ����� ����� ����������
if ( ! function_exists( 'v_dump' ) ) {
	function v_dump() {
		if ( DEBUG_MODE && is_callable( $func = array( 'Inter_Error', 'var_dump' ) ) ) {
			$variables = func_get_args();
			call_user_func( $func, $variables );
		}
	}
}
/** Test Begins **/
// echo $test_test; // Notice
// trigger_error('��� ����' , E_USER_ERROR ); // User_Error
// throw new Exception('this is a test'); // Uncaught Exception
// echo fatal(); // Fatal Error.
/** -------------------------------------------------------------------------------------------*/

// ��� �������� ������������� �� ��������
$GLOBALS['tbl_posts']	=	"gb_posts";		// ��� ������� � �����������
$GLOBALS['tbl_replies']	=	"gb_replies";	// ��� ������� � ��������
$GLOBALS['tbl_users']	=	"gb_users";		// ��� ������� � ������������

require_once( __DIR__ . '/func.php' );
// ������������� ����
$db = Mysqli_Db::getInstance( Mysqli_Db::get_param());