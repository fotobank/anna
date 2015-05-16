<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 *
 * Кэшируемый автозагрузчик классов
 * Имена классов должны совпадать с именами файлов
 * Файлы классов могут распологаться в произвольных папках без привязки имени к директориям и namespace
 * Конфликт одинаковых имен разных классов необходимо решать с помощью задания разных namespace для классов
 * Класс следит за перемещением файлов и корректирует кэш
 *
 */

namespace Core;

use Exception;

/**
 * Class Autoloader
 * @package yourNameSpace
 */
class Autoloader
{

	// папка кэша и лога
	public static $dir_cashe = 'cache/autoload';

	// имя файла кеша без слэша
	public static $file_array_class_cache = 'class_cache.php';

	// файлы в заданных директориях отобранные по маске
	public static $file_array_scan_files = 'scan_files.php';

	// файл лога создается при новом рекурсивном сканировании классов или если класс не найден
	public static $fileLog = 'log.html';

	// расширение файла класса
	public static $files_ext = [
		'.php',
		'.class.php'
	];

	// массив путей поиска файлов классов
	public static $paths = [
		'classes',
		'system'
	];

	// кэш соответствия неймспейса пути в файловой системе
	protected static $array_class_cache = [];

	// массив всех файлов в сканируемых папкак, отобранных по заданным расширениям
	protected static $array_scan_files = [];


	/**
	 * конструктор класса
	 */
	public function __construct()
		{

			try {
				self::$dir_cashe = SITE_PATH.self::$dir_cashe.DIRSEP;
				self::$fileLog = self::$dir_cashe.self::$fileLog;
				self::$file_array_class_cache = self::$dir_cashe.self::$file_array_class_cache;
				self::$file_array_scan_files = self::$dir_cashe.self::$file_array_scan_files;

				// если файла нет - создать
				self::checkExistsFile(self::$file_array_class_cache);
				/** читаем кэш в массив из файла */
				self::$array_class_cache = self::getFileMap();

				if (self::checkExistsFile(self::$file_array_scan_files)) {
					self::updateScanFiles();
				} else {
					self::$array_scan_files = self::arrFromFile(self::$file_array_scan_files);
				}

			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param $className
	 * автозагрузчик файлов классов
	 *
	 * @throws Exception
	 */
	public static function autoload($className)
		{
			try {
				// флаг нахождения файла если false - файл найден
				$flag = true;
				// подготовка имени в классах с namespace
				$lastNsPos = strrpos($className, '\\');
				if ($lastNsPos) {
					$namespace = str_replace(['\\', '/'], DIRSEP, substr($className, 0, $lastNsPos));
					$className = substr($className, $lastNsPos + 1);
					$namespace = DIRSEP.$namespace;
					self::findClass($className, $namespace, $flag);
				}
				// попытка поиска без namespace ( если namespace отличается от вложенности директорий )
				if ($flag) {
					self::findClass($className, "", $flag);
				}

			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param      $className
	 * @param bool $namespace
	 * @param      $flag
	 *
	 * @throws Exception
	 */
	private static function findClass($className, $namespace = false, &$flag)
		{
			foreach (self::$files_ext as $ext) {
				// проверка нахождения класса в кэш
				self::checkClassNameInCash($className, $ext, $flag);
				if (false === $flag) {
					break;
				}
				self::checkClassNameInBaseScanFiles($className, $namespace, $ext, $flag);
				if ($flag) {
					self::updateScanFiles();
					self::checkClassNameInBaseScanFiles($className, $namespace, $ext, $flag);
				}
				if ($flag) {
					// сообщение log класс не найден
					self::logLoadError($className);
					throw new Exception("класс <b>'".$className."'</b> не найден");
				}
				if (false === $flag) {
					break;
				}
			}
		}

	/**
	 * @param $className
	 * @param $ext
	 *
	 * проверка нахождения класса в кэш
	 *
	 * @param $flag
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected static function checkClassNameInCash($className, $ext, &$flag)
		{
			try {
				if (!empty(self::$array_class_cache[$className])) {
					$filePath = self::$array_class_cache[$className].DIRSEP.$className.$ext;
					if (file_exists($filePath)) {
						/** @noinspection PhpIncludeInspection */
						require_once $filePath;

						$flag = false;
					}
				}
			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param $className
	 * @param $namespace
	 * @param $flag
	 * @param $ext
	 *
	 * @throws Exception
	 */
	private static function checkClassNameInBaseScanFiles($className, $namespace, $ext, &$flag)
		{
			if (self::$array_scan_files[$className]) {
				// проверка с namespase
				if ($namespace) {
					foreach (self::$paths as $path) {
						$path_class = SITE_PATH.$path.$namespace;
						self::checkClass($path_class, $className, $ext, $flag);
						if (false === $flag) {
							break;
						}
					}
				}
				// пробуем найти класс без namespase
				if($flag) {
					foreach (self::$array_scan_files[$className] as $path_class) {
						self::checkClass($path_class, $className, $ext, $flag);
						if (false === $flag) {
							break;
						}
					}
				}
			}
		}

	/**
	 * сканирование всех заданных директорий для создания массива файлов с заданными расширениями
	 * @throws Exception
	 */
	protected static function updateScanFiles()
		{
			foreach (self::$paths as $path) {
				self::$array_scan_files = self::rScanDir(SITE_PATH.$path.DIRSEP);
				self::arrToFile(self::$array_scan_files, self::$file_array_scan_files);
				self::updateScanFilesLog();
			}
		}

	/**
	 * @param string $base
	 * @param array  $data
	 * Функция позволяет получить ассоциированный массив всех файлов в заданной директории и поддиректориях
	 * example: echo '<pre>'; var_export(rScanDir(dirname(__FILE__).'/')); echo '</pre>';
	 *
	 * @return array
	 * @throws Exception
	 */
	private static function rScanDir($base = '', &$data = [])
		{
			static $data;
			$base = str_replace(['\\', '/'], DIRSEP, $base);
			try {
				$array = array_diff(scandir($base), ['.', '..']);
				foreach ($array as $value) :

					if (is_dir($base.$value)) :
						$data = rscandir($base.$value.DIRSEP, $data);

					elseif (is_file($base.$value)) :
						foreach (self::$files_ext as $mask) {
							$path_parts = pathinfo($value);
							$extension = isset($path_parts['extension']) ? $path_parts['extension'] : false;
							if ($mask == '.'.$extension) {
								$data[$path_parts['filename']][] = rtrim($base, DIRSEP);
							}
						}
					endif;
				endforeach;
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					throw new Exception("не найдена директория сканирования файлов ".$e->getMessage()."<br>", E_USER_ERROR);
				}
			}
			return $data;
		}

	/**
	 * void arrToFile - функция записи массива в файл
	 *
	 * @param mixed  $value    - объект, массив и т.д.
	 * @param string $filename - имя файла куда будет произведена запись данных
	 *
	 * @return void
	 *
	 */
	protected static function arrToFile($value, $filename)
		{
			$str_value = serialize($value);

			$f = fopen($filename, 'w');
			fwrite($f, $str_value);
			fclose($f);
		}

	/**
	 * mixed arrFromFile - функция восстановления данных массива из файла
	 *
	 * @param string $filename - имя файла откуда будет производиться восстановление данных
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected static function arrFromFile($filename)
		{
			try {
				$file = file_get_contents($filename);
				$value = unserialize($file);

				return $value;
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					throw new Exception("не найден путь файла '{$filename}' <br>");
				}
			}

			return null;
		}

	/**
	 * @param $file
	 * установка прав на директорию и файлы
	 * если файла нет - создать
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected static function checkExistsFile($file)
		{
			if (!is_writable(self::$dir_cashe)) {
				chmod(self::$dir_cashe, 0711);
			}
			if (!file_exists($file)) {
				try {
					file_put_contents($file, "", LOCK_EX);
					chmod($file, 0600);

					return true;
				}
				catch (Exception $e) {
					if (DEBUG_MODE) {
						throw new Exception("can not create '{$file}' an unwritable dir '".self::$dir_cashe."'<br>");
					}
				}
			}

			return false;
		}

	/**
	 * чтение файла кэша в массив
	 * @return array|bool|null
	 * @throws Exception
	 */
	private static function getFileMap()
		{
			try {
				$file_string = file_get_contents(self::$file_array_class_cache);
				$file_array = parse_ini_string($file_string);
				if ($file_array === []) {
					return null;
				}

				return $file_array;
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					throw new Exception("Can not read the file <br>", E_USER_ERROR);
				}
			}

			return null;
		}

	/**
	 * @param $full_path
	 * @param $file_name
	 * @param $ext
	 *
	 * @param $flag
	 *
	 * @return bool проверка наличия файла класса в директории
	 *
	 * проверка физичесского наличия файла класса в директории
	 * @throws Exception
	 */
	private static function checkClass($full_path, $file_name, $ext, &$flag)
		{
			try {
				$file = $full_path.DIRSEP.$file_name.$ext;
				self::logFindClass($full_path, $file_name.$ext);
				if (file_exists($file)) {

					/** @noinspection PhpIncludeInspection */
					require_once($file);
					self::logLoadOk($full_path.DIRSEP, $file_name.$ext);
					self::addNamespace($file_name, $full_path);
					self::putFileMap($file_name." = ".$full_path.PHP_EOL);
					$flag = false;
				}
			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param $name_space
	 * @param $full_path
	 *
	 * @return bool
	 *
	 * добавление найденного пути класса в массив
	 */
	public static function addNamespace($name_space, $full_path)
		{
			if (is_dir($full_path)) {
				self::$array_class_cache[$name_space] = $full_path;

				return true;
			}

			return false;
		}

	/**
	 * @param $class
	 * запись кэша в файл
	 *
	 * @throws Exception
	 */
	private static function putFileMap($class)
		{
			try {
				// если строки в записи не не равны - изменить запись в файле
				if (self::checkFileMap($class)) {
					// а если не существуют - добавить
					file_put_contents(self::$file_array_class_cache, $class, FILE_APPEND | LOCK_EX);
				}
			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param $data
	 *
	 * @return bool
	 * проверка существования записи в файле кэша классов и, если надо, изменение строк
	 * @throws Exception
	 */
	private static function checkFileMap($data)
		{
			try {
				$data = trim($data);
				$fileMap = self::getFileMap();
				list($file_name, $file_patch) = explode("=", $data);
				$file_patch = trim($file_patch);
				$file_name = trim($file_name);

				if ($fileMap && isset($fileMap[$file_name])) {
					$fullNameMap = $file_name." = ".$fileMap[$file_name];
					// если пути не равны
					if ($fullNameMap != $data) {
						// изменить строку в массиве и записать изменения в файл
						$fileMap[$file_name] = $file_patch;
						$fileMapWrite = "";
						foreach ($fileMap as $class => $file) {
							$fileMapWrite .= $class." = ".$file.PHP_EOL;
						}
						// перезаписываем файл
						file_put_contents(self::$file_array_class_cache, $fileMapWrite, LOCK_EX);
						unset($fileMap);
					}

					return false;
				}
			}
			catch (Exception $e) {
				self::echoErr($e);
			}

			// разрешить запись
			return true;
		}

	/**
	 * @param $data
	 *
	 * запись лога в файл
	 *
	 * @throws Exception
	 */
	private static function putLog($data)
		{
			try {
				$data = ("[ ".$data." => ".date('d.m.Y H:i:s')." ]<br>".PHP_EOL);
				file_put_contents(self::$fileLog, $data, FILE_APPEND | LOCK_EX);
			}
			catch (Exception $e) {
				self::echoErr($e);
			}
		}

	/**
	 * @param $full_path
	 * @param $file
	 *
	 * запись успешного подключения класса в лог
	 */
	private static function logLoadOk($full_path, $file)
		{
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
	private static function logFindClass($file_path, $file)
		{
			if (DEBUG_MODE) {
				self::putLog(('ищем файл <b>"'.$file.'"</b> in '.$file_path));
			}
		}

	/**
	 * запись в лог техничесского сообщения
	 */
	private static function updateScanFilesLog()
		{
			if (DEBUG_MODE) {
				self::putLog((
				'<br><b style="background-color: #ffffaa;">сканируем директории и обновляем базу поиска классов</b>'));
			}
		}

	/**
	 * @param $file_name
	 *
	 * запись ошибки в лог
	 */
	private static function logLoadError($file_name)
		{
			if (DEBUG_MODE) {
				self::putLog(('<br><b style="color: #ff0000;">Класс "'.$file_name.'" не найден</b><br>'));
			}
		}


	/**
	 * вывод ошибок на экран
	 *
	 * @param $e
	 */
	private static function echoErr($e)
		{
			if (DEBUG_MODE) {
				$trace = str_replace("#1", "<br>1 [ошибка вызвана в файле]: ", $e->getTraceAsString());
				$trace = str_replace("#", "<br>", $trace);
				$trace = str_replace("(", "(<b>", $trace);
				$trace = str_replace(")", "</b>)", $trace);
				die ("<b>Ошибка:</b> ".$e->getMessage()." в файле '".$e->getFile()."' на линии <b>'".$e->getLine().
					 "'</b>"."<br><b>Trace:</b>".$trace);
			}
		}
}

\spl_autoload_register('Core\Autoloader::autoload');