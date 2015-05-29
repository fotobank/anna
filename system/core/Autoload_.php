<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 * To change this template use File | Settings | File Templates.
 */


class Autoload {


	/**
	 *  инициализация
	 */
	public function __construct() {

		spl_autoload_extensions( ".php" );
	//	spl_autoload_register( [ "autoload", "autoload_class" ] );
	//	spl_autoload_register( [ "autoload", "autoloadController" ] );
		spl_autoload_register( [ "autoload", "__autoload" ] ); // новый автолоад
	}

	/**
	 * @param $className
	 *
	 * @return bool
	 */
	protected function autoload_class($className) {
		$className = ltrim( $className, '\\' );
		$fileName  = '';
		$namespace = '';

		/*if (strpos($className, 'Twig') === 0) {
			return false;
		}*/

		$lastNsPos = strrpos( $className, '\\' );
		if ( $lastNsPos ) {
			$namespace = substr( $className, 0, $lastNsPos );
			$className = substr( $className, $lastNsPos + 1 );
			$fileName  = str_replace( '\\', DS, $namespace ) . DS;
		}
		$fileName .= str_replace( '_', DS, $className ) . '.php';
		$file = SITE_PATH . 'classes' . DS . $fileName;

		try {
			require_once  $file;
		} catch ( Exception $e ) {
			if ( DEBUG_MODE ) {
				trigger_error( $e->getMessage() , E_USER_ERROR );
			}
		}
		return true;
	}

	/**
	 * @param $className
	 */
	protected function autoloadController($className) {
		$filename = "controllers/" . $className . ".php";
		if (is_readable($filename)) {
			require_once  $filename;
		}
	}

	/**
	 * @param $className
	 *
	 * @return bool
	 */
	function __autoload($className) {
		$extensions = [ ".php", ".class.php" ];
		$paths = [ "system/web","system/classes", "system/core", "system/classes/pattern", "classes" ];
		$fileName  = '';

		$lastNsPos = strrpos( $className, '\\' );
		if ( $lastNsPos ) {
			$namespace = substr( $className, 0, $lastNsPos );
			$className = substr( $className, $lastNsPos + 1 );
			$fileName  = str_replace( '\\', DS, $namespace ) . DS;
		}
			$fileName .= str_replace("_" , DS, $className);


		$load_ok = 0;
		foreach ($paths as $path) {
			$path  = str_replace( ['\\','/'], DS, $path );
			if(1 === $load_ok ) break;
			$file = SITE_PATH. $path . DS . $fileName;
			foreach ($extensions as $ext) {
				try {
					if (is_readable($file . $ext)) {
						require_once $file . $ext;
						$load_ok = 1;
						break;
					}
				} catch ( Exception $e ) {
					if ( DEBUG_MODE ) {
						trigger_error( $e->getMessage() , E_USER_ERROR );
					}
				}
			}
		}
	}
}

new Autoload();