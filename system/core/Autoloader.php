<?php
/**
 * Класс предназначен для автоматичесской загрузки классов
 *
 * @created   by PhpStorm
 * @package   https://github.com/fotobank/anna.od.ua/blob/master/system/core/Autoloader.php
 * @version   1.12
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     16.05.15
 * @time      :     05:47
 * @license   MIT License: http://opensource.org/licenses/MIT
 *
 * Интеллектуальный, кэшируещий, быстрый автозагрузчик классов.
 * Имена классов должны совпадать с именами файлов.
 * Файлы классов могут распологаться в произвольных папках без привязки имени к директориям и namespace.
 * Конфликт одинаковых имен разных классов необходимо решать с помощью задания разных namespace для классов.
 * Класс следит за перемещением файлов и корректирует кэш.
 *
 * Перед началом поиска создается база данных $file_array_scan_files всех
 * файлов в заданных в $paths путях, попадающих под маску в $files_ext.
 * При первой загрузке создается кэш удачных подключений в файле $file_array_class_cache.
 * Автозагрузчик сначала ищет классы в кэше, затем перебирает с путем в namespace,
 * если не находит - ищет рекуксивным сканированием.
 * Если и в этом случае не находит файл класса - обновляет базу $file_array_scan_files и пробует все сначала.
 * При неудаче - выбрасывает ошибку.
 *
 */

namespace Core;

use Exception;

/**
 * Class Autoloader
 */
class Autoloader
{

	// папка кэша и лога
	public $dir_cashe = 'cache/autoload';

	// имя файла кеша без слэша
	public $file_array_class_cache = 'class_cache.php';

	// файлы в заданных директориях отобранные по маске
	public $file_array_scan_files = 'scan_files.php';

	// файл лога создается при новом рекурсивном сканировании классов или если класс не найден
	public $fileLog = 'log.html';

	// расширение файла класса
	public $files_ext = [
		'.php',
		'.class.php'
	];

	// массив путей поиска файлов классов
	public $paths = [
		'classes',
		'system'
	];

	// файл настройки
	public $htaccess = '.htaccess';

	// данные файла настройки
	public $htaccess_data = <<<END
<Files *.html, *.php>
Order deny,allow
Deny from all
</Files>
END;

	// кэш соответствия неймспейса пути в файловой системе
	protected $array_class_cache = [];

	// массив всех файлов в сканируемых папкак, отобранных по заданным расширениям
	protected $array_scan_files = [];


	/**
	 * конструктор класса
	 */
	public function __construct()
		{

			try {
				spl_autoload_extensions(".php");
				/** назначаем метод автозагрузки */
				spl_autoload_register(["Core\\Autoloader", "autoload"]);

				/** переопределение свойств  */
				$this->dir_cashe = SITE_PATH.str_replace(['\\', '/'], DIRSEP, $this->dir_cashe).DIRSEP;
				$this->fileLog = $this->dir_cashe.$this->fileLog;
				$this->file_array_class_cache = $this->dir_cashe.$this->file_array_class_cache;
				$this->file_array_scan_files = $this->dir_cashe.$this->file_array_scan_files;
				$this->htaccess = $this->dir_cashe.$this->htaccess;
				/** проверить директории кэша и задать права */
				$this->checkDir();
				/** проверка и создание .htaccess */
				$this->createFile($this->htaccess, $this->htaccess_data);
				/** если файла кэша нет - создать */
				$this->createFile($this->file_array_class_cache, '');
				/** читаем кэш в массив из файла */
				$this->array_class_cache = $this->getFileMap();

				if ($this->createFile($this->file_array_scan_files, '')) {
					$this->updateScanFiles();
				} else {
					$this->array_scan_files = $this->arrFromFile($this->file_array_scan_files);
				}

			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param $class_name
	 * автозагрузчик файлов классов
	 *
	 * @throws Exception
	 */
	public function autoload($class_name)
		{
			try {
				/** флаг нахождения файла если false - файл найден */
				$flag = true;
				/** подготовка имени в классах с namespace */
				$lastNsPos = strrpos($class_name, '\\');
				if ($lastNsPos) {
					$name_space = str_replace(['\\', '/'], DIRSEP, substr($class_name, 0, $lastNsPos));
					$class_name = substr($class_name, $lastNsPos + 1);
					$name_space = DIRSEP.$name_space;
					$this->findClass($class_name, $name_space, $flag);
				}
				/** попытка поиска без namespace ( если namespace отличается от вложенности директорий ) */
				if ($flag) {
					$this->findClass($class_name, "", $flag);
				}

			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param      $class_name
	 * @param bool $name_space
	 * @param      $flag
	 *
	 * @throws Exception
	 */
	private function findClass($class_name, $name_space = false, &$flag)
		{
			foreach ($this->files_ext as $ext) {
				/** проверка нахождения класса в кэш */
				$this->checkClassNameInCash($class_name, $ext, $flag);
				if (false === $flag) {
					break;
				}
				$this->checkClassNameInBaseScanFiles($class_name, $name_space, $ext, $flag);
				if ($flag) {
					$this->updateScanFiles();
					$this->checkClassNameInBaseScanFiles($class_name, $name_space, $ext, $flag);
				}
				if ($flag) {
					/** сообщение log класс не найден */
					$this->logLoadError($class_name);
					throw new Exception("класс <b>'".$class_name."'</b> не найден");
				}
				if (false === $flag) {
					break;
				}
			}
		}

	/**
	 * @param $class_name
	 * @param $ext
	 *
	 * проверка нахождения класса в кэш
	 *
	 * @param $flag
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected function checkClassNameInCash($class_name, $ext, &$flag)
		{
			try {
				if (!empty($this->array_class_cache[$class_name])) {
					$filePath = $this->array_class_cache[$class_name].DIRSEP.$class_name.$ext;
					if (file_exists($filePath)) {
						/** @noinspection PhpIncludeInspection */
						require_once $filePath;

						$flag = false;
					}
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param $class_name
	 * @param $name_space
	 * @param $flag
	 * @param $ext
	 *
	 * @throws Exception
	 */
	private function checkClassNameInBaseScanFiles($class_name, $name_space, $ext, &$flag)
		{
			try {
				if (isset($this->array_scan_files[$class_name])) {
					/** проверка с namespase */
					if ($name_space) {
						foreach ($this->paths as $path) {
							$path_class = SITE_PATH.$path.$name_space;
							$this->checkClass($path_class, $class_name, $ext, $flag);
							if (false === $flag) {
								break;
							}
						}
					}
					/** ищем класс с незаданным namespase */
					if ($flag) {
						foreach ($this->array_scan_files[$class_name] as $path_class) {
							$this->checkClass($path_class, $class_name, $ext, $flag);
							if (false === $flag) {
								break;
							}
						}
					}
				} else {
					throw new Exception('класс <b>"'.$class_name.'"</b> не найден ');
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * создание массива файлов заданных директорий
	 * с фильтрацией по расширению
	 * @throws Exception
	 */
	protected function updateScanFiles()
		{
			foreach ($this->paths as $path) {
				$path = str_replace(['\\', '/'], DIRSEP, $path);
				$this->array_scan_files = $this->rScanDir(SITE_PATH.$path.DIRSEP);
			}
			$this->arrToFile($this->array_scan_files, $this->file_array_scan_files);
			$this->updateScanFilesLog();
		}

	/**
	 * @param string $base
	 * Функция позволяет получить ассоциированный массив всех файлов в заданной директории и поддиректориях
	 * example: echo '<pre>'; var_export(rScanDir(dirname(__FILE__).'/')); echo '</pre>';
	 *
	 * @param array  $data
	 *
	 * @return array
	 */
	private function rScanDir($base = '', &$data = [])
		{
			static $data;
			try {
				if (is_dir($base)) {
					$array = array_diff(scandir($base), ['.', '..']);
					foreach ($array as $value) {

						if (is_dir($base.$value)) {
							$data = $this->rScanDir($base.$value.DIRSEP, $data);

						} elseif (is_file($base.$value)) {
							foreach ($this->files_ext as $mask) {
								$path_parts = pathinfo($value);
								$extension = isset($path_parts['extension']) ? $path_parts['extension'] : false;
								if ($mask == '.'.$extension) {
									$data[$path_parts['filename']][] = rtrim($base, DIRSEP);
								}
							}
						}
					}
				} else {
					throw new Exception("не найдена директория сканирования файлов <br>");
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
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
	protected function arrToFile($value, $filename)
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
	protected function arrFromFile($filename)
		{
			try {
				if (file_exists($filename)) {
					$file = file_get_contents($filename);
					$value = unserialize($file);

					return $value;
				}
				throw new Exception("не найден путь файла '{$filename}' <br>");
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}

			return null;
		}

	/**
	 * проверить директории и установить права
	 */
	protected function checkDir()
		{
			try {
				if (!is_dir($this->dir_cashe)) {
					mkdir($this->dir_cashe, 0711, true);
				}
				if (!is_writable($this->dir_cashe)) {
					chmod($this->dir_cashe, 0711);
				}
				if (!is_dir($this->dir_cashe) || !is_writable($this->dir_cashe)) {
					throw new Exception('can not create "'.$this->dir_cashe.'" an unwritable dir <br>');
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param $file
	 *
	 * если файла нет - создать и установить права
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	protected function createFile($file, $data)
		{
			try {
				if (!file_exists($file)) {
					file_put_contents($file, $data, LOCK_EX);
					if (!file_exists($file)) {
						throw new Exception("can not create '{$file}' an unwritable dir '".$this->dir_cashe."'<br>");
					}
					chmod($file, 0600);

					return true;
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}

			return false;
		}

	/**
	 * чтение файла кэша в массив
	 * @return array|bool|null
	 * @throws Exception
	 */
	private function getFileMap()
		{
			$file_array = [];
			try {
				$file_string = file_get_contents($this->file_array_class_cache);
				if ($file_string === false) {
					throw new Exception('Can not read the file <b>"'.$this->file_array_class_cache.'"</b>');
				}
				$file_array = parse_ini_string($file_string);
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}

			return $file_array;
		}

	/**
	 * @param $full_path
	 * @param $file_name
	 * @param $ext
	 *
	 * @param $flag
	 *
	 * @return bool
	 *
	 * проверка физичесского наличия файла класса в директории
	 * и запись кэша
	 * @throws Exception
	 */
	private function checkClass($full_path, $file_name, $ext, &$flag)
		{
			try {
				$file = $full_path.DIRSEP.$file_name.$ext;
				$this->logFindClass($full_path, $file_name.$ext);
				if (file_exists($file)) {

					/** @noinspection PhpIncludeInspection */
					require_once($file);
					$this->logLoadOk($full_path.DIRSEP, $file_name.$ext);
					$this->addNamespace($file_name, $full_path);
					$this->putFileMap($file_name." = ".$full_path.PHP_EOL);
					$flag = false;
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
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
	public function addNamespace($name_space, $full_path)
		{
			if (is_dir($full_path)) {
				$this->array_class_cache[$name_space] = $full_path;
			}
		}

	/**
	 * @param $class
	 *
	 * @return bool проверка существования записи в файле кэша классов и, если надо, изменение строк
	 * проверка существования записи в файле кэша классов и, если надо, изменение строк
	 * @internal param $data
	 *
	 */
	private function putFileMap($class)
		{
			try {
				$class = trim($class);
				$file_map = $this->getFileMap();
				list($file_name, $file_patch) = explode("=", $class);
				$file_patch = trim($file_patch);
				$file_name = trim($file_name);

				if (isset($file_map[$file_name])) {
					$full_name_map = $file_name." = ".$file_map[$file_name];
					/** если пути не равны */
					if ($full_name_map != $class) {
						/** изменить строку в массиве и записать изменения в файл */
						$file_map[$file_name] = $file_patch;
						$file_map_write = "";
						foreach ($file_map as $drop_name_class => $file) {
							$file_map_write .= $drop_name_class." = ".$file.PHP_EOL;
						}
						/** перезаписываем файл */
						file_put_contents($this->file_array_class_cache, $file_map_write, LOCK_EX);
						unset($file_map);
					}
				} else {
					/** или добавить запись */
					file_put_contents($this->file_array_class_cache, $class.PHP_EOL, FILE_APPEND | LOCK_EX);
				}
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param $data
	 *
	 * запись лога в файл
	 *
	 * @throws Exception
	 */
	private function putLog($data)
		{
			try {
				$data = ("[ ".$data." => ".date('d.m.Y H:i:s')." ]<br>".PHP_EOL);
				file_put_contents($this->fileLog, $data, FILE_APPEND | LOCK_EX);
			}
			catch (Exception $e) {
				$this->echoErr($e);
			}
		}

	/**
	 * @param $full_path
	 * @param $file
	 *
	 * запись успешного подключения класса в лог
	 */
	private function logLoadOk($full_path, $file)
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="color: #23a126;">подключили </b> '.'<b style="color: #3a46e1;"> '.
							   $full_path.' </b>'.'<b style="color: #ff0000;">'.$file.'</b><br>'));
			}
		}

	/**
	 * @param $file_path
	 * @param $file
	 *
	 * запись в лог начала поиска файла
	 */
	private function logFindClass($file_path, $file)
		{
			if (DEBUG_MODE) {
				$this->putLog(('ищем файл <b>"'.$file.'"</b> in '.$file_path));
			}
		}

	/**
	 * запись в лог техничесского сообщения
	 */
	private function updateScanFilesLog()
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="background-color: #ffffaa;">сканируем директории и обновляем базу поиска классов</b>'));
			}
		}

	/**
	 * @param $file_name
	 *
	 * запись ошибки в лог
	 */
	private function logLoadError($file_name)
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="color: #ff0000;">Класс "'.$file_name.'" не найден</b><br>'));
			}
		}

	/**
	 * вывод ошибок на экран
	 *
	 * @param $e
	 */
	private function echoErr(Exception $e)
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
