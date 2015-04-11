<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 * To change this template use File | Settings | File Templates.
 */
//    require_once (__DIR__.'/../classes/go/DB/autoload.php');
// автозагрузка классов

class autoload {

	static private $instance = NULL;

	/**
	 * function Singleton
	 * Создание объекта в единственном экземпляре
	 *
	 * @return  autoload|null
	 *
	 */

	static function getInstance() {
		if ( self::$instance == NULL ) {
			self::$instance = new autoload();
		}
		return self::$instance;
	}


	/**
	 *
	 */
	protected function __construct() {

		if ( version_compare( phpversion(), '5.1.0', '<' ) == true ) {
			die ( 'PHP5.1 Only' );
		}
		// Константы:
		define ( 'DIRSEP', DIRECTORY_SEPARATOR );
		if(!defined('SITE_PATH')) {
			define ( 'SITE_PATH',  realpath( __DIR__ . DIRSEP . '..' . DIRSEP ) . DIRSEP );
		}
		// Узнаём путь до файлов сайта



		spl_autoload_extensions( ".php" );
		spl_autoload_register( [ "autoload", "autoload_class" ] );
	//	spl_autoload_register(array("autoload", "autoloadController"));
	//	spl_autoload_register(array("autoload", "__autoload")); // новый автолоад
	}

	protected function autoload_class($className) {
		$className = ltrim( $className, '\\' );
		$fileName  = '';
		$namespace = '';

		/*if (strpos($className, 'Twig') === 0) {
			return false;
		}*/

		if ( $lastNsPos = strrpos( $className, '\\' ) ) {
			$namespace = substr( $className, 0, $lastNsPos );
			$className = substr( $className, $lastNsPos + 1 );
			$fileName  = str_replace( '\\', DIRSEP, $namespace ) . DIRSEP;
		}
		$fileName .= str_replace( '_', DIRSEP, $className ) . '.php';
		$file = SITE_PATH . 'classes' . DIRSEP . $fileName;

		try {
			require_once  $file;
		} catch ( Exception $e ) {
			if ( DEBUG_MODE ) {
				trigger_error( $e->getMessage() , E_USER_ERROR );
			}
		}
		return true;
	}

	protected function autoloadController($className) {
		$filename = "controllers/" . $className . ".php";
		if (is_readable($filename)) {
			require_once  $filename;
		}
	}

	function __autoload($className) {
		$extensions = array(".php", ".class.php");
		$paths = array("classes", "inc");

		if ( $lastNsPos = strrpos( $className, '\\' ) ) {
			$namespace = substr( $className, 0, $lastNsPos );
			$className = substr( $className, $lastNsPos + 1 );
			$fileName  = str_replace( '\\', DIRSEP, $namespace ) . DIRSEP;
		}

		$fileName = str_replace("_" , DIRSEP, $className);
		foreach ($paths as $path) {
			$file = SITE_PATH. $path . DIRSEP . $fileName;
			foreach ($extensions as $ext) {
				try {
					if (is_readable($file . $ext)) {
						require_once $file . $ext;
						break;
					}
				} catch ( Exception $e ) {
					if ( DEBUG_MODE ) {
						trigger_error( $e->getMessage() , E_USER_ERROR );
					}
				}
			}
		}
		return true;
	}

	function __destruct() {
	}

	/**
	 *  __clone()
	 */
	protected function __clone() {
	}

	/**
	 *  __wakeup()
	 */
	protected function __wakeup() {
	}

}