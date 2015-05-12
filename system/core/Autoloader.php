<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 *
 * Кэшируемый автозагрузчик классов
 * Имена классов должны совпадать с именами файлов
 * Файлы классов могут распологаться в произвольных папках без привязки к директориям и namespace
 * Конфликт одинаковых имен разных классов необходимо решать с помощью задания namespace для классов
 *
 */

namespace core;

/**
 * Class Autoloader
 * @package yourNameSpace
 */
class Autoloader {

	// папка кэша и лога
	public static $dirCashe = "cache/autoload/";

	// имя файла кеша без слэша
	public static $fileMap = "classLog.php";

	// файл лога создается при новом рекурсивном сканировании классов или если класс не найден
	public static $fileLog = "log.html";

	// расширение файла класса
	public static $arrauFilesExtensions = [
		".php",
		".class.php"
	];

	// массив путей поиска файлов классов
	public static $paths = [
		"classes/Ubench",
		"classes/Alex",
		"classes/Inter",
		"system/core/Mysqli",
		"classes",
		"system"

	];

	// кэш соответствия неймспейса пути в файловой системе
	protected static $nameSpacesMap = [];

	// файл существует и успешно создан
	protected static $exists = false;

	// флаг проверки чтения папки кэша
	protected static $isDirWritable = false;

	// флаг проверки на запись
	protected static $isWritable = false;

	// флаг на чтение
	protected static $isReadable = false;


	/**
	 * конструктор класса
	 */
	public function __construct() {

		self::$dirCashe = SITE_PATH.self::$dirCashe;
		self::$fileMap  = self::$dirCashe.self::$fileMap;

		// Set some flags about this file
		self::$isDirWritable = is_writable(self::$dirCashe);
		if (!self::$isDirWritable) {
			chmod(self::$dirCashe, 0777);
			self::$isDirWritable = is_writable(self::$dirCashe);
		};
		self::$exists = file_exists(self::$fileMap);
		// если файла нет - создать
		if (!self::$exists) {
			if (self::$isDirWritable) {
				file_put_contents(self::$fileMap, "", LOCK_EX);
				chmod(self::$fileMap, 0666);
			} else {
				trigger_error("Can not write contents to an unwritable dir".self::$dirCashe);
			}
		}
		self::$isWritable = is_writable(self::$fileMap);
		self::$isReadable = is_readable(self::$fileMap);

	}

	/**
	 * @param $className
	 *
	 * @return bool
	 *
	 * автозагрузчик файлов классов
	 */
	public static function autoload($className) {
		$namespace = '';
		$lastNsPos = strrpos($className, '\\');
		if ($lastNsPos) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			//	$file_name = str_replace( '\\', DIRSEP, $namespace ) . DIRSEP;
			$namespace = DIRSEP.$namespace;
		}
		if (self::$isReadable) {
			self::$nameSpacesMap = self::getFileMap(); //читаем кэш в массив
		} else {
			trigger_error("Can not read contents to an readable file".self::$fileMap);
		}

		$flag = true;
		foreach (self::$arrauFilesExtensions as $ext) {
			foreach (self::$paths as $path) {
			    	$full_path = SITE_PATH.str_replace(['\\', '/'], DIRSEP, $path);
				debugHC( $full_path, 'full_path' );
				$flag      = self::checkClassNameInCash($className, $ext); // проверка нахождения класса в кэш
				if (false === $flag) {
					break;
				}
				$flag = self::checkClass($full_path.$namespace, $className, $ext); // проверка пути класса
				if (false === $flag) {
					break;
				}
				self::logFindStart(); // лог - начало сканировния
				self::recursiveClassAutoload($full_path, $namespace, $className, $ext, $flag); // рекурсивное сканирование папок
				if (false === $flag) {
					break;
				}
			}
			if (false === $flag) {
				break;
			}
		}
		self::logLoadError($flag, $className); // сообщение loga класс не найден
	}


	/**
	 * @param $file_path
	 * @param $file_name
	 * @param $ext
	 * @param $flag
	 * @param $namespace
	 *
	 * рекурсивное сканирование заданных директорий
	 *
	 */
	public static function recursiveClassAutoload($file_path, $namespace, $file_name, $ext, &$flag) {
		if (is_dir($file_path) && false !== ($handle = opendir($file_path)) && $flag) {
			while (false !== ($dir = readdir($handle)) && $flag) {

				if (strpos($dir, '.') === false) {
					$full_path = $file_path.DIRSEP.$dir;
					$flag      = self::checkClass($full_path.$namespace, $file_name, $ext);
					if (false === $flag) {
						break;
					}
					self::recursiveClassAutoload($full_path, $namespace, $file_name, $ext, $flag);
				}
			}
			closedir($handle);
		}
	}

	/**
	 * @param $className
	 * @param $ext
	 *
	 * проверка нахождения класса в кэш
	 *
	 * @return bool
	 */
	protected static function checkClassNameInCash($className, $ext) {

		if (!empty(self::$nameSpacesMap[$className])) {
			$filePath = self::$nameSpacesMap[$className].DIRSEP.$className.$ext;
			if (file_exists($filePath)) {
				/** @noinspection PhpIncludeInspection */
				require_once $filePath;

				return false;
			}
		}

		return true;
	}

	/**
	 * чтение файла кэша в массив
	 * @return array|bool|null
	 */
	private static function getFileMap() {

		if (self::$isReadable) {
			$file_string = file_get_contents(self::$fileMap);
			$file_array  = parse_ini_string($file_string);
			if ($file_array === []) {
				return null;
			}

			return $file_array;
		} else {
			trigger_error("Can not read the file.");
		}

		return false;

	}

	/**
	 * @param $full_path
	 * @param $file_name
	 * @param $ext
	 *
	 * @return bool
	 *
	 * проверка наличия файла класса в директории
	 *
	 */
	private static function checkClass($full_path, $file_name, $ext) {
		try {
			$file = $full_path.DIRSEP.$file_name.$ext;
			self::logFindClass($full_path, $file_name.$ext);
			if (file_exists($file)) {

				/** @noinspection PhpIncludeInspection */
				require_once($file);
				self::logLoadOk($full_path.DIRSEP, $file_name.$ext);
				self::addNamespace($file_name, $full_path);
				self::putFileMap($file_name." = ".$full_path."\n");

				return false;

			}
		}
		catch (\Exception $e) {
			if (DEBUG_MODE) {
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}

		return true;
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 * проверка существования записи в файле кэша
	 */
	private static function checkFileMap($data) {

		$fileMap = self::getFileMap();
		list($file_name, $file_patch) = explode("=", $data);
		$file_patch = trim($file_patch);
		$file_name  = trim($file_name);
		if ($fileMap && isset($fileMap[$file_name]) == $file_patch) {
			return false;
		}

		return true;

	}

	/**
	 * @param $name_space
	 * @param $full_path
	 *
	 * @return bool
	 *
	 * добавление найденного пути класса в массив
	 */
	public static function addNamespace($name_space, $full_path) {
		if (is_dir($full_path)) {
			self::$nameSpacesMap[$name_space] = $full_path;

			return true;
		}

		return false;
	}

	/**
	 * @param $file_patch
	 *
	 * запись кэша в файл
	 */
	private static function putFileMap($file_patch) {

		if (self::$isWritable) {
			// если запись не существует - записать в файл
			if (self::checkFileMap($file_patch)) {
				self::putFile(self::$fileMap, $file_patch);
			}

		} else {
			trigger_error("Can not write contents to an unwritable file".self::$fileMap);
		}
	}

	/**
	 * @param $data
	 *
	 * запись лога в файл
	 */
	private static function putLog($data) {

		$file_path = self::$dirCashe.self::$fileLog;
		$data      = ("[ ".$data." => ".date('d.m.Y H:i:s')." ]<br>".PHP_EOL);
		self::putFile($file_path, $data);
	}

	/**
	 * @param $file_path
	 * @param $data
	 *
	 * служебная функция
	 * запись файла на диск
	 */
	private static function putFile($file_path, $data) {

		$file = fopen($file_path, 'a');
		flock($file, LOCK_EX);
		fwrite($file, ($data));
		flock($file, LOCK_UN);
		fclose($file);

	}

	/**
	 * @param $full_path
	 * @param $file
	 *
	 * запись успешного подключения класса в лог
	 */
	private static function logLoadOk($full_path, $file) {

		if (DEBUG_MODE) {
			self::putLog(('<br><b style="color: #23a126;">подключили </b> '.'<b style="color: #3a46e1;"> "'.
				$full_path.'" </b>'.'<b style="color: #ff0000;">'.$file.'</b><br>'));
		}

	}

	/**
	 * @param $file_path
	 * @param $file
	 *
	 * запись в лог начала поиска файла
	 */
	private static function logFindClass($file_path, $file) {
		if (DEBUG_MODE) {
			self::putLog(('ищем файл <b>"'.$file.'"</b> in '.$file_path));
		}
	}

	/**
	 * запись в лог техничесского сообщения
	 */
	private static function logFindStart() {
		if (DEBUG_MODE) {
			self::putLog(('<br><b style="background-color: #ffffaa;">начинаем рекурсивный поиск</b>'));
		}
	}

	/**
	 * @param $flag
	 * @param $file_name
	 *
	 * запись ошибки в лог
	 */
	private static function logLoadError($flag, $file_name) {
		if (DEBUG_MODE && $flag) {
			self::putLog(('<br><b style="color: #ff0000;">Класс "'.$file_name.'" не найден</b><br>'));
		}
	}

}

\spl_autoload_register('core\Autoloader::autoload');