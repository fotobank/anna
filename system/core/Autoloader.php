<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 * To change this template use File | Settings | File Templates.
 */

namespace core;

/**
 * Class Autoloader
 * @package yourNameSpace
 */
class Autoloader {

	protected static $namespacesMap = [ ]; // кэш соответствия неймспейса пути в файловой системе
	protected static $exists = false; 	// файл существует и успешно создан
	protected static $is_dir_writable = false; // флаг проверки чтения папки кэша
	protected static $is_writable = false; // флаг проверки на запись
	protected static $is_readable = false; // флаг на чтение

	public static $dir_cashe = "cache/classes/"; // папка кэша и лога
	public static $fileMap = "classLog.php"; // полный путь и имя файла кеша
	public static $file_log  = "log.html"; // файл лога создается при новом рекурсивном сканировании классов или если класс не найден
	public static $extensions = [ ".php" ]; // расширение файла
	public static $paths = [ "classes", "system/web", "system/classes", "system/core", "system/classes/pattern", "system" ];

	const debug = 1; // отладка для создания файла лога

	/**
	 *
	 */
	public function __construct() {

		self::$dir_cashe = SITE_PATH . self::$dir_cashe;
		self::$fileMap = self::$dir_cashe . self::$fileMap;

		// Set some flags about this file
		self::$is_dir_writable = is_writable(self::$dir_cashe);
		if(!self::$is_dir_writable) {
			chmod (self::$dir_cashe, 0777);
			self::$is_dir_writable = is_writable(self::$dir_cashe);
		};
		self::$exists = file_exists(self::$fileMap);
		// если файла нет - создать
		if(!self::$exists) {
			if ( self::$is_dir_writable ) {
				file_put_contents( self::$fileMap, "", LOCK_EX );
				chmod (self::$fileMap, 0666);
			} else {
				trigger_error( "Can not write contents to an unwritable dir" . self::$dir_cashe );
			}
		}
		self::$is_writable = is_writable(self::$fileMap);
		self::$is_readable = is_readable(self::$fileMap);

	}

	/**
	 * @param $namespace
	 * @param $rootDir
	 *
	 * @return bool
	 */
	public static function addNamespace( $namespace, $rootDir ) {
		if ( is_dir( $rootDir ) ) {
			self::$namespacesMap[$namespace] = $rootDir;
			return true;
		}
		return false;
	}

	/**
	 * @param $className
	 * @param $ext
	 *
	 * @return bool
	 */
	protected static function check_className_in_cash( $className, $ext ) {
		$pathParts = explode( '\\', $className );
		if ( is_array( $pathParts ) ) {
			$baseName = array_pop( $pathParts );
			if ( !empty( self::$namespacesMap[$baseName] ) ) {
				$filePath = self::$namespacesMap[$baseName] . DIRSEP . $baseName  . $ext;
				/** @noinspection PhpIncludeInspection */
				require_once $filePath;
				return false;
			}
		}
		return true;
	}

	/**
	 * @param $className
	 *
	 * @return bool
	 */
	public static function autoload( $className ) {
		$namespace = '';
		$file_name  = $className;
		$namespaceClass = $className;

		$lastNsPos = strrpos( $className, '\\' );
		if ( $lastNsPos ) {
			$namespace = substr( $className, 0, $lastNsPos );
			$className = substr( $className, $lastNsPos + 1 );
			$file_name = str_replace( '\\', DIRSEP, $namespace ) . DIRSEP;
		}

		if ( self::$is_readable ) {
			self::$namespacesMap = self::get_file_map(); //читаем кэш в массив
		} else {
			trigger_error( "Can not read contents to an readable file" . self::$fileMap );
		}

		$flag = TRUE;
		foreach ( self::$paths as $path ) {
			if ( false === $flag ) break;
			$path      = str_replace( [ '\\', '/' ], DIRSEP, $path );
			$full_path = SITE_PATH . $path;
			foreach ( self::$extensions as $ext ) {
				if ( false === $flag ) break;
				$flag = self::check_className_in_cash( $namespaceClass, $ext ); // проверка нахождения класса в кэш
				if ( !$flag ) break;
				self::check_class( $className, $ext, $flag, $full_path . DIRSEP . $namespace ); // проверка пути класса
				self::put_finf_start(); // лог начало сканировния
				Autoloader::recursive_autoload( $full_path, $file_name, $ext, $flag ); // рекурсивное сканирование папок
			}
		}
		self::put_load_error( $flag, $file_name ); // сообщение loga класс не найден
	}

	/**
	 * @param $file_path
	 * @param $file_name
	 * @param $ext
	 * @param $flag
	 */
	public static function recursive_autoload( $file_path, $file_name, $ext, &$flag ) {
		if ( is_dir( $file_path ) && false !== ( $handle = opendir( $file_path ) ) && $flag ) {
			while ( false !== ( $dir = readdir( $handle ) ) && $flag ) {

				if ( strpos( $dir, '.' ) === false ) {
					$full_path = $file_path . DIRSEP . $dir;
					self::check_class( $file_name, $ext, $flag, $full_path );
					if ( !$flag ) break;
					Autoloader::recursive_autoload( $full_path, $file_name, $ext, $flag );
				}
			}
			closedir( $handle );
		}
	}

	/**
	 * @param $file_name
	 * @param $ext
	 * @param $flag
	 * @param $full_path
	 */
	private static function check_class( $file_name, $ext, &$flag, $full_path ) {
		try {
			$file = $full_path . DIRSEP . $file_name . $ext;
			self::put_find_class( $full_path, $file_name . $ext );
			if ( file_exists( $file ) ) {

				/** @noinspection PhpIncludeInspection */
				require_once( $file );
				self::put_load_ok( $full_path . DIRSEP, $file_name . $ext );
				self::addNamespace( $file_name, $full_path . DIRSEP );
				self::put_file_map( $file_name." = ". $full_path );
				$flag = false;

			}
		} catch ( \Exception $e ) {
			if ( DEBUG_MODE ) {
				trigger_error( $e->getMessage(), E_USER_ERROR );
			}
		}
	}

	/**
	 * чтение айла в массив
	 * @return array|bool|null
	 */
	private static function get_file_map( ) {

		if(self::$is_readable) {
			$file_string = file_get_contents(self::$fileMap);
			$file_array = parse_ini_string($file_string);
			if ( $file_array === [ ] ) return NULL;
			return $file_array;
		} else {
			trigger_error("Can not read the file.");
		}
		return false;

	}

	/**
	 * @param $data
	 */
	private static function put_file_map( $data ) {

		if ( self::$is_writable ) {
			$data = $data . "\n";
			self::put_file( self::$fileMap, $data );
		} else {
			trigger_error( "Can not write contents to an unwritable file" . self::$fileMap );
		}
	}

	/**
	 * @param $data
	 */
	private static function put_file_log( $data ) {

		$file_path = self::$dir_cashe . self::$file_log;
		$data = ( '[ ' . $data . '=>' . date( 'd.m.Y H:i:s' ) . ' ]' . PHP_EOL );
		self::put_file( $file_path, $data );
	}

	/**
	 * @param $file_path
	 * @param $data
	 */
	private static function put_file( $file_path, $data ) {

		$file = fopen( $file_path, 'a' );
		flock( $file, LOCK_EX );
		fwrite( $file, ( $data ) );
		flock( $file, LOCK_UN );
		fclose( $file );

	}

	/**
	 * @param $flag
	 * @param $file_name
	 */
	private static function put_load_error( $flag, $file_name ) {
		if ( Autoloader::debug && $flag ) {
			Autoloader::put_file_log(
				(
					'<br><b style="color: #ff0000;">файл ' . $file_name . ' не найден</b><br>'
				)
			);
		}
	}

	/**
	 * @param $full_path
	 * @param $file
	 */
	private static function put_load_ok( $full_path, $file ) {

		if ( Autoloader::debug ) {
			Autoloader::put_file_log(
				(
					'<br><b style="color: #3ebd45;">подключили</b> ' .
					$full_path . DIRSEP . '<b style="color: #3ebd45;">' . $file . '</b><br>'
				)
			);
		}

	}

	private static function put_finf_start() {
		if ( Autoloader::debug ) Autoloader::put_file_log( ( '<br><b style="background-color: #ffffaa;">начинаем рекурсивный поиск</b>' ) );
	}

	/**
	 * @param $file_path
	 * @param $file
	 */
	private static function put_find_class( $file_path, $file ) {
		if ( Autoloader::debug ) Autoloader::put_file_log( ( 'ищем файл <b>' . $file . '</b> in ' . $file_path ) );
	}

}

\spl_autoload_register( 'core\Autoloader::autoload' );