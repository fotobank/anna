<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jurii
 * Date: 05.09.13
 * Time: 13:52
 *
 * ���������� ������������� �������
 * ����� ������� ������ ��������� � ������� ������
 * ����� ������� ����� ������������� � ������������ ������ ��� �������� ����� � ����������� � namespace
 * �������� ���������� ���� ������ ������� ���������� ������ � ������� ������� ������ namespace ��� �������
 * ����� ������ �� ������������ ������ � ������������ ���
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

	// ����� ���� � ����
	public static $dir_cashe = 'cache/autoload';

	// ��� ����� ���� ��� �����
	public static $file_array_class_cache = 'class_cache.php';

	// ����� � �������� ����������� ���������� �� �����
	public static $file_array_scan_files = 'scan_files.php';

	// ���� ���� ��������� ��� ����� ����������� ������������ ������� ��� ���� ����� �� ������
	public static $fileLog = 'log.html';

	// ���������� ����� ������
	public static $files_ext = [
		'.php',
		'.class.php'
	];

	// ������ ����� ������ ������ �������
	public static $paths = [
		'classes',
		'system'
	];

	// ��� ������������ ���������� ���� � �������� �������
	protected static $array_class_cache = [];

	// ������ ���� ������ � ����������� ������, ���������� �� �������� �����������
	protected static $array_scan_files = [];


	/**
	 * ����������� ������
	 */
	public function __construct()
		{

			try {
				self::$dir_cashe = SITE_PATH.self::$dir_cashe.DIRSEP;
				self::$fileLog = self::$dir_cashe.self::$fileLog;
				self::$file_array_class_cache = self::$dir_cashe.self::$file_array_class_cache;
				self::$file_array_scan_files = self::$dir_cashe.self::$file_array_scan_files;

				// ���� ����� ��� - �������
				self::checkExistsFile(self::$file_array_class_cache);
				/** ������ ��� � ������ �� ����� */
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
	 * ������������� ������ �������
	 *
	 * @throws Exception
	 */
	public static function autoload($className)
		{
			try {
				// ���� ���������� ����� ���� false - ���� ������
				$flag = true;
				// ���������� ����� � ������� � namespace
				$lastNsPos = strrpos($className, '\\');
				if ($lastNsPos) {
					$namespace = str_replace(['\\', '/'], DIRSEP, substr($className, 0, $lastNsPos));
					$className = substr($className, $lastNsPos + 1);
					$namespace = DIRSEP.$namespace;
					self::findClass($className, $namespace, $flag);
				}
				// ������� ������ ��� namespace ( ���� namespace ���������� �� ����������� ���������� )
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
				// �������� ���������� ������ � ���
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
					// ��������� log ����� �� ������
					self::logLoadError($className);
					throw new Exception("����� <b>'".$className."'</b> �� ������");
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
	 * �������� ���������� ������ � ���
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
				// �������� � namespase
				if ($namespace) {
					foreach (self::$paths as $path) {
						$path_class = SITE_PATH.$path.$namespace;
						self::checkClass($path_class, $className, $ext, $flag);
						if (false === $flag) {
							break;
						}
					}
				}
				// ������� ����� ����� ��� namespase
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
	 * ������������ ���� �������� ���������� ��� �������� ������� ������ � ��������� ������������
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
	 * ������� ��������� �������� ��������������� ������ ���� ������ � �������� ���������� � ��������������
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
					throw new Exception("�� ������� ���������� ������������ ������ ".$e->getMessage()."<br>", E_USER_ERROR);
				}
			}
			return $data;
		}

	/**
	 * void arrToFile - ������� ������ ������� � ����
	 *
	 * @param mixed  $value    - ������, ������ � �.�.
	 * @param string $filename - ��� ����� ���� ����� ����������� ������ ������
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
	 * mixed arrFromFile - ������� �������������� ������ ������� �� �����
	 *
	 * @param string $filename - ��� ����� ������ ����� ������������� �������������� ������
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
					throw new Exception("�� ������ ���� ����� '{$filename}' <br>");
				}
			}

			return null;
		}

	/**
	 * @param $file
	 * ��������� ���� �� ���������� � �����
	 * ���� ����� ��� - �������
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
	 * ������ ����� ���� � ������
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
	 * @return bool �������� ������� ����� ������ � ����������
	 *
	 * �������� ������������ ������� ����� ������ � ����������
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
	 * ���������� ���������� ���� ������ � ������
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
	 * ������ ���� � ����
	 *
	 * @throws Exception
	 */
	private static function putFileMap($class)
		{
			try {
				// ���� ������ � ������ �� �� ����� - �������� ������ � �����
				if (self::checkFileMap($class)) {
					// � ���� �� ���������� - ��������
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
	 * �������� ������������� ������ � ����� ���� ������� �, ���� ����, ��������� �����
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
					// ���� ���� �� �����
					if ($fullNameMap != $data) {
						// �������� ������ � ������� � �������� ��������� � ����
						$fileMap[$file_name] = $file_patch;
						$fileMapWrite = "";
						foreach ($fileMap as $class => $file) {
							$fileMapWrite .= $class." = ".$file.PHP_EOL;
						}
						// �������������� ����
						file_put_contents(self::$file_array_class_cache, $fileMapWrite, LOCK_EX);
						unset($fileMap);
					}

					return false;
				}
			}
			catch (Exception $e) {
				self::echoErr($e);
			}

			// ��������� ������
			return true;
		}

	/**
	 * @param $data
	 *
	 * ������ ���� � ����
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
	 * ������ ��������� ����������� ������ � ���
	 */
	private static function logLoadOk($full_path, $file)
		{
			if (DEBUG_MODE) {
				self::putLog(('<br><b style="color: #23a126;">���������� </b> '.'<b style="color: #3a46e1;"> "'.
							  $full_path.'" </b>'.'<b style="color: #ff0000;">'.$file.'</b><br>'));
			}
		}

	/**
	 * @param $file_path
	 * @param $file
	 *
	 * ������ � ��� ������ ������ �����
	 */
	private static function logFindClass($file_path, $file)
		{
			if (DEBUG_MODE) {
				self::putLog(('���� ���� <b>"'.$file.'"</b> in '.$file_path));
			}
		}

	/**
	 * ������ � ��� ������������� ���������
	 */
	private static function updateScanFilesLog()
		{
			if (DEBUG_MODE) {
				self::putLog((
				'<br><b style="background-color: #ffffaa;">��������� ���������� � ��������� ���� ������ �������</b>'));
			}
		}

	/**
	 * @param $file_name
	 *
	 * ������ ������ � ���
	 */
	private static function logLoadError($file_name)
		{
			if (DEBUG_MODE) {
				self::putLog(('<br><b style="color: #ff0000;">����� "'.$file_name.'" �� ������</b><br>'));
			}
		}


	/**
	 * ����� ������ �� �����
	 *
	 * @param $e
	 */
	private static function echoErr($e)
		{
			if (DEBUG_MODE) {
				$trace = str_replace("#1", "<br>1 [������ ������� � �����]: ", $e->getTraceAsString());
				$trace = str_replace("#", "<br>", $trace);
				$trace = str_replace("(", "(<b>", $trace);
				$trace = str_replace(")", "</b>)", $trace);
				die ("<b>������:</b> ".$e->getMessage()." � ����� '".$e->getFile()."' �� ����� <b>'".$e->getLine().
					 "'</b>"."<br><b>Trace:</b>".$trace);
			}
		}
}

\spl_autoload_register('Core\Autoloader::autoload');