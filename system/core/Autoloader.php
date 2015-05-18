<?php
/**
 * ����� ������������ ��� ��������������� �������� �������
 *
 * @created   by PhpStorm
 * @package   https://github.com/fotobank/anna.od.ua/blob/master/system/core/Autoloader.php
 * @version   1.2
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     16.05.15
 * @time      :     05:47
 * @license   MIT License: http://opensource.org/licenses/MIT
 *
 * ����������������, ����������, ������� ������������� �������.
 * ����� ������� ������ ��������� � ������� ������.
 * ����� ������� ����� ������������� � ������������ ������ ��� �������� ����� � ����������� � namespace,
 *            �� ����������� ������, ���� ������ ����� ���������� �����.
 * �������� ���������� ���� ������ ������� ���������� ������ � ������� ������� ������ namespace ��� �������.
 * ����� ������ �� ������������ ������ � ������������ ���.
 *
 * ����� ������� ������ ��������� ���� ������ $file_array_scan_files ����
 * ������ � �������� � $paths �����, ���������� ��� ����� � $files_ext.
 * ��� ������ �������� ��������� ��� ������� ����������� � ����� $file_array_class_cache.
 * ������������� ������� ���� ������ � ����, ����� ���������� � ����� � namespace,
 * ���� �� ������� - ���� ����������� �������������.
 * ���� � � ���� ������ �� ������� ���� ������ - ��������� ���� $file_array_scan_files � ������� ��� �������.
 * ��� ������� - ����������� ������.
 *
 */

namespace Core;

use Exception;

/**
 * Class Autoloader
 */
class Autoloader
{

	// ����� ���� � ����
	public $dir_cashe = 'cache/autoload';

	// ��� ����� ���� ��� �����
	public $file_array_class_cache = 'class_cache.php';

	// ����� � �������� ����������� ���������� �� �����
	public $file_array_scan_files = 'scan_files.php';

	// ���� ���� ��������� ��� ����� ����������� ������������ ������� ��� ���� ����� �� ������
	public $fileLog = 'log.html';

	// ���������� ����� ������
	public $files_ext = [
		'.php',
		'.class.php'
	];

	// ������ ����� ������ ������ �������
	public $paths = [
		'classes',
		'system'
	];

	// ���� ���������
	public $htaccess = '.htaccess';

	// ������ ����� ���������
	public $htaccess_data = <<<END
<Files *.html, *.php>
Order deny,allow
Deny from all
</Files>
END;

	// ��� ������������ ���������� ���� � �������� �������
	protected $array_class_cache = [];

	// ������ ���� ������ � ����������� ������, ���������� �� �������� �����������
	protected $array_scan_files = [];


	/**
	 * ����������� ������
	 */
	public function __construct()
		{
			spl_autoload_extensions(".php");
			/** ��������� ����� ������������ */
			spl_autoload_register(["Core\\Autoloader", "autoload"]);

			/** ��������������� �������  */
			$this->dir_cashe = SITE_PATH . str_replace(['\\', '/'], DIRSEP, $this->dir_cashe) . DIRSEP;
			$this->fileLog = $this->dir_cashe . $this->fileLog;
			$this->file_array_class_cache = $this->dir_cashe . $this->file_array_class_cache;
			$this->file_array_scan_files = $this->dir_cashe . $this->file_array_scan_files;
			$this->htaccess = $this->dir_cashe . $this->htaccess;
			/** ��������� ���������� ���� � ������ ����� */
			$this->checkDir();
			/** �������� � �������� .htaccess */
			$this->createFile($this->htaccess, $this->htaccess_data);
			/** ���� ����� ���� ��� - ������� */
			$this->createFile($this->file_array_class_cache, '');
			/** ������ ��� � ������ �� ����� */
			$this->array_class_cache = $this->getFileMap();

			if ($this->createFile($this->file_array_scan_files, '') != false) {
				$this->updateScanFiles();
			} else {
				$this->array_scan_files = $this->arrFromFile($this->file_array_scan_files);
			}
		}

	/**
	 * @param $class_name
	 * ������������� ������ �������
	 *
	 * @throws Exception
	 */
	public function autoload($class_name)
		{
			$this->tryCatch(function () use ($class_name) {

				/** ���������� ����� � ������� � namespace */
				$lastNsPos = strrpos($class_name, '\\');
				if ($lastNsPos) {
					$name_space = str_replace(['\\', '/'], DIRSEP, substr($class_name, 0, $lastNsPos));
					$class_name = substr($class_name, $lastNsPos + 1);
					$name_space = DIRSEP . $name_space;
					$this->findClass($class_name, $name_space);

					return;
				}

				/** ������� ������ ��� namespace ( ���� namespace ���������� �� ����������� ���������� ) */
				$this->findClass($class_name, "");

			});
		}

	/**
	 * @param      $class_name
	 * @param bool $name_space
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function findClass($class_name, $name_space = false)
		{
			foreach ($this->files_ext as $ext) {

				/** �������� ���������� ������ � ��� */
				if (false === $this->checkClassNameInCash($class_name, $ext)) {
					return false;
				}

				if (false === $this->checkScanFiles($class_name, $name_space, $ext)) {
					return false;
				}

				/** ��������� log ����� �� ������ */
				$this->logLoadError($class_name);
				throw new Exception("����� <b>'" . $class_name . "'</b> �� ������");

			}

			return true;
		}

	/**
	 * @param $class_name
	 * @param $ext
	 *
	 * �������� ���������� ������ � ���
	 *
	 * @return bool
	 * @throws Exception
	 */
	protected function checkClassNameInCash($class_name, $ext)
		{
			return $this->tryCatch(function () use ($class_name, $ext) {
				if (!empty($this->array_class_cache[$class_name])) {
					$filePath = $this->array_class_cache[$class_name] . DIRSEP . $class_name . $ext;
					if (file_exists($filePath)) {

						/** @noinspection PhpIncludeInspection */
						require_once $filePath;

						return false;
					}
				}

				return true;
			});
		}

	/**
	 * @param $class_name
	 * @param $name_space
	 * @param $ext
	 *
	 * �������� ������� $class_name � $array_scan_files
	 * ���� ����� �� ������ - �������� ��� � ��������� ��� ���
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function checkScanFiles($class_name, $name_space, $ext)
		{
			return $this->tryCatch(function () use ($class_name, $name_space, $ext) {

				if (isset($this->array_scan_files[$class_name])) {

					return $this->checkClassNameInBaseScanFiles($name_space, $class_name, $ext);

				} else {
					/** �������� ���������� � ���� ������������ ������������ */
					$this->updateScanFiles();
					/** ��������� ��� ��� */
					if (isset($this->array_scan_files[$class_name])) {

						return $this->checkClassNameInBaseScanFiles($name_space, $class_name, $ext);

					} else {
						throw new Exception('����� <b>"' . $class_name . '"</b> �� ������ ');
					}
				}
			});
		}

	/**
	 * @param $name_space
	 * @param $class_name
	 * @param $ext
	 *
	 * @return bool
	 */
	function checkClassNameInBaseScanFiles($name_space, $class_name, $ext)
		{
			/** �������� � namespase */

			if ($name_space) {
				foreach ($this->paths as $path) {
					$path_class = SITE_PATH . $path . $name_space;

					if (false === $this->checkClass($path_class, $class_name, $ext)) {
						return false;
					}
				}
			}
			/** ���� ����� � ���������� namespase */

			foreach ($this->array_scan_files[$class_name] as $path_class) {

				if (false === $this->checkClass($path_class, $class_name, $ext)) {
					return false;
				}
			}

			return true;
		}

	/**
	 * �������� ������� ������ �������� ����������
	 * � ����������� �� ����������
	 * @throws Exception
	 */
	protected function updateScanFiles()
		{
			foreach ($this->paths as $path) {
				$path = str_replace(['\\', '/'], DIRSEP, $path);
				$this->array_scan_files = $this->rScanDir(SITE_PATH . $path . DIRSEP);
			}
			$this->arrToFile($this->array_scan_files, $this->file_array_scan_files);
			$this->updateScanFilesLog();
		}

	/**
	 * @param string $base
	 * ������� ��������� �������� ��������������� ������ ���� ������ � �������� ���������� � ��������������
	 * example: echo '<pre>'; var_export(rScanDir(dirname(__FILE__).'/')); echo '</pre>';
	 *
	 * @param array  $data
	 *
	 * @return array
	 */
	private function rScanDir($base = '', &$data = [])
		{
			static $data;
			$this->tryCatch(function () use ($base, &$data) {
				if (is_dir($base)) {
					$array = array_diff(scandir($base), ['.', '..']);
					foreach ($array as $value) {

						if (is_dir($base . $value)) {
							$data = $this->rScanDir($base . $value . DIRSEP, $data);

						} elseif (is_file($base . $value)) {
							foreach ($this->files_ext as $mask) {
								$path_parts = pathinfo($value);
								$extension = isset($path_parts['extension']) ? $path_parts['extension'] : false;
								if ($mask == '.' . $extension) {
									$data[$path_parts['filename']][] = rtrim($base, DIRSEP);
								}
							}
						}
					}
				} else {
					throw new Exception("�� ������� ���������� ������������ ������ <br>");
				}
			});

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
	protected function arrToFile($value, $filename)
		{
			$str_value = serialize($value);
			file_put_contents($filename, $str_value, LOCK_EX);
		}

	/**
	 * mixed arrFromFile - ������� �������������� ������ ������� �� �����
	 *
	 * @param string $filename - ��� ����� ������ ����� ������������� �������������� ������
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected function arrFromFile($filename)
		{
			return $this->tryCatch(function () use ($filename) {
				if (file_exists($filename)) {

					$file = file_get_contents($filename);
					$value = unserialize($file);

					if ($value === false) {
						$this->updateScanFiles();
						$file = file_get_contents($filename);
						$value = unserialize($file);
					}

					return $value;
				}
				throw new Exception("�� ������ ���� ����� '{$filename}' <br>");
			});
		}

	/**
	 * ��������� ���������� � ���������� �����
	 */
	protected function checkDir()
		{
			$this->tryCatch(function () {
				if (!is_dir($this->dir_cashe)) {
					mkdir($this->dir_cashe, 0711, true);
				}
				if (!is_writable($this->dir_cashe)) {
					chmod($this->dir_cashe, 0711);
				}
				if (!is_dir($this->dir_cashe) || !is_writable($this->dir_cashe)) {
					throw new Exception('can not create "' . $this->dir_cashe . '" an unwritable dir <br>');
				}
			});
		}

	/**
	 * @param $file
	 *
	 * ���� ����� ��� - ������� � ���������� �����
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	protected function createFile($file, $data)
		{
			return $this->tryCatch(function () use ($file, $data) {
				if (!file_exists($file)) {
					file_put_contents($file, $data, LOCK_EX);
					if (!file_exists($file)) {
						throw new Exception("can not create '{$file}' an unwritable dir '" . $this->dir_cashe .
											"'<br>");
					}
					chmod($file, 0600);

					return true;
				}

				return false;
			});
		}

	/**
	 * ������ ����� ���� � ������
	 * @return array|bool|null
	 * @throws Exception
	 */
	private function getFileMap()
		{
			return $this->tryCatch(function () {

				$file_string = file_get_contents($this->file_array_class_cache);
				if ($file_string === false) {
					throw new Exception('Can not read the file <b>"' . $this->file_array_class_cache . '"</b>');
				}

				return parse_ini_string($file_string);
			});

		}

	/**
	 * @param $full_path
	 * @param $file_name
	 * @param $ext
	 *
	 *
	 * @return bool
	 *
	 * �������� ������������ ������� ����� ������ � ����������
	 * � ������ ����
	 * @throws Exception
	 */
	private function checkClass($full_path, $file_name, $ext)
		{
			return $this->tryCatch(function () use ($full_path, $file_name, $ext) {
				$file = $full_path . DIRSEP . $file_name . $ext;
				$this->logFindClass($full_path, $file_name . $ext);
				if (file_exists($file)) {

					/** @noinspection PhpIncludeInspection */
					require_once($file);
					$this->logLoadOk($full_path . DIRSEP, $file_name . $ext);
					$this->addNamespace($file_name, $full_path);
					$this->putFileMap($file_name . " = " . $full_path . PHP_EOL);

					return false;
				}

				return true;
			});
		}

	/**
	 * @param $name_space
	 * @param $full_path
	 *
	 * @return bool
	 *
	 * ���������� ���������� ���� ������ � ������
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
	 * @return bool �������� ������������� ������ � ����� ���� ������� �, ���� ����, ��������� �����
	 * �������� ������������� ������ � ����� ���� ������� �, ���� ����, ��������� �����
	 * @internal param $data
	 *
	 */
	private function putFileMap($class)
		{
			$this->tryCatch(function () use ($class) {
				$class = trim($class);
				$file_map = $this->getFileMap();
				list($file_name, $file_patch) = explode("=", $class);
				$file_patch = trim($file_patch);
				$file_name = trim($file_name);

				if (isset($file_map[$file_name])) {
					$full_name_map = $file_name . " = " . $file_map[$file_name];
					/** ���� ���� �� ����� */
					if ($full_name_map != $class) {
						/** �������� ������ � ������� � �������� ��������� � ���� */
						$file_map[$file_name] = $file_patch;
						$file_map_write = "";
						foreach ($file_map as $drop_name_class => $file) {
							$file_map_write .= $drop_name_class . " = " . $file . PHP_EOL;
						}
						/** �������������� ���� */
						file_put_contents($this->file_array_class_cache, $file_map_write, LOCK_EX);
						unset($file_map);
					}
				} else {
					/** ��� �������� ������ */
					file_put_contents($this->file_array_class_cache, $class . PHP_EOL, FILE_APPEND | LOCK_EX);
				}
			});
		}

	/**
	 * @param $data
	 *
	 * ������ ���� � ����
	 *
	 * @throws Exception
	 */
	private function putLog($data)
		{
			$this->tryCatch(function () use ($data) {
				$data = ("[ " . $data . " => " . date('d.m.Y H:i:s') . " ]<br>" . PHP_EOL);
				file_put_contents($this->fileLog, $data, FILE_APPEND | LOCK_EX);
			});
		}

	/**
	 * @param $full_path
	 * @param $file
	 *
	 * ������ ��������� ����������� ������ � ���
	 */
	private function logLoadOk($full_path, $file)
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="color: #23a126;">���������� </b> ' . '<b style="color: #3a46e1;"> ' .
							   $full_path . ' </b>' . '<b style="color: #ff0000;">' . $file . '</b><br>'));
			}
		}

	/**
	 * @param $file_path
	 * @param $file
	 *
	 * ������ � ��� ������ ������ �����
	 */
	private function logFindClass($file_path, $file)
		{
			if (DEBUG_MODE) {
				$this->putLog(('���� ���� <b>"' . $file . '"</b> in ' . $file_path));
			}
		}

	/**
	 * ������ � ��� ������������� ���������
	 */
	private function updateScanFilesLog()
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="background-color: #ffffaa;">��������� ���������� � ��������� ���� ������ �������</b>'));
			}
		}

	/**
	 * @param $file_name
	 *
	 * ������ ������ � ���
	 */
	private function logLoadError($file_name)
		{
			if (DEBUG_MODE) {
				$this->putLog(('<br><b style="color: #ff0000;">����� "' . $file_name . '" �� ������</b><br>'));
			}
		}

	/**
	 * ������� ��������� ������
	 *
	 * @param $closure
	 *
	 * @return bool
	 */
	protected function tryCatch($closure)
		{
			try {
				return $closure();
			}
			catch (Exception $e) {
				if (DEBUG_MODE) {
					$this->echoErr($e);
				}

				return false;
			}
		}

	/**
	 * ����� ������ �� �����
	 *
	 * @param $e
	 */
	private function echoErr(Exception $e)
		{
			$trace = str_replace("#1", "<br>1 [������ ������� � �����]: ", $e->getTraceAsString());
			$trace = str_replace("#", "<br>", $trace);
			$trace = str_replace("(", "(<b>", $trace);
			$trace = str_replace(")", "</b>)", $trace);
			die ("<b>������:</b> " . $e->getMessage() . " ���������� �������� � ����� '" . $e->getFile() .
				 "' �� ����� <b>'" . $e->getLine() . "'</b>" . "<br><b>Trace:</b>" . $trace);
		}
}
