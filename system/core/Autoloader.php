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


/**
 * Class Autoloader
 * @package yourNameSpace
 */
class Autoloader
{

	// ����� ���� � ����
	public static $dirCashe = "cache/autoload/";

	// ��� ����� ���� ��� �����
	public static $fileMap = "classLog.php";

	// ���� ���� ��������� ��� ����� ����������� ������������ ������� ��� ���� ����� �� ������
	public static $fileLog = "log.html";

	// ���������� ����� ������
	public static $arrauFilesExtensions = [
		".php",
		".class.php"
	];

	// ������ ����� ������ ������ �������
	public static $paths = [
		"classes/Ubench",
		"classes/Alex",
		"classes/Inter",
		"system/core/Mysqli",
		"classes",
		"system"

	];

	// ��� ������������ ���������� ���� � �������� �������
	protected static $nameSpacesMap = [];

	// ���� ���������� � ������� ������
	protected static $exists = false;

	// ���� �������� ������ ����� ����
	protected static $isDirWritable = false;

	// ���� �������� �� ������
	protected static $isWritable = false;

	// ���� �� ������
	protected static $isReadable = false;


	/**
	 * ����������� ������
	 */
	public function __construct()
		{

			self::$dirCashe = SITE_PATH.self::$dirCashe;
			self::$fileMap = self::$dirCashe.self::$fileMap;

			chmod(self::$dirCashe, 0711);
			// Set some flags about this file
			self::$isDirWritable = is_writable(self::$dirCashe);

			self::$exists = file_exists(self::$fileMap);
			// ���� ����� ��� - �������
			if (!self::$exists) {
				if (self::$isDirWritable) {
					file_put_contents(self::$fileMap, "", LOCK_EX);
					chmod(self::$fileMap, 0600);
				} else {
					throw new \Exception("Can not write contents to an unwritable dir '". self::$dirCashe."'<br>");
				}
			}
			self::$isWritable = is_writable(self::$fileMap);
			self::$isReadable = is_readable(self::$fileMap);

			/** ������ ��� � ������ �� ����� */
			if (self::$isReadable) {
				self::$nameSpacesMap = self::getFileMap();
			} else {
				throw new \Exception("Can not read contents to an readable file '".self::$fileMap."'<br>");
			}

		}


	/**
	 * @param $className
	 * ������������� ������ �������
	 *
	 * @throws \Exception
	 */
	public static function autoload($className)
		{
			try {
				$flag = true;
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

				// ��������� log ����� �� ������
				if ($flag) {
					self::logLoadError($flag, $className);
					throw new \Exception("Error in ".__METHOD__." �lass ".$className." not found <br>");
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception($e->getMessage(), E_USER_ERROR);
				}
			}
		}


	/**
	 * @param $file_path
	 * @param $namespace
	 *
	 * ����������� ������������ �������� ����������
	 * @param $file_name
	 * @param $ext
	 * @param $flag
	 *
	 * @throws \Exception
	 */
	public static function recursiveClassAutoload($file_path, $namespace, $file_name, $ext, &$flag)
		{
			try {
				if (is_dir($file_path) && false !== ($handle = opendir($file_path)) && $flag) {
					while (false !== ($dir = readdir($handle)) && $flag) {

						if (strpos($dir, '.') === false) {
							$full_path = $file_path.DIRSEP.$dir;
							$flag = self::checkClass($full_path.$namespace, $file_name, $ext);
							if (false === $flag) {
								break;
							}
							self::recursiveClassAutoload($full_path, $namespace, $file_name, $ext, $flag);
						}
					}
					closedir($handle);
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage(), E_USER_ERROR);
				}
			}
		}

	/**
	 * @param $className
	 * @param $ext
	 *
	 * �������� ���������� ������ � ���
	 *
	 * @return bool
	 * @throws \Exception
	 */
	protected static function checkClassNameInCash($className, $ext)
		{
			try {
				if (!empty(self::$nameSpacesMap[$className])) {
					$filePath = self::$nameSpacesMap[$className].DIRSEP.$className.$ext;
					if (file_exists($filePath)) {
						/** @noinspection PhpIncludeInspection */
						require_once $filePath;

						return false;
					}
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage(), E_USER_ERROR);
				}
			}

			return true;
		}

	/**
	 * ������ ����� ���� � ������
	 * @return array|bool|null
	 * @throws \Exception
	 */
	private static function getFileMap()
		{
			if (self::$isReadable) {
				$file_string = file_get_contents(self::$fileMap);
				$file_array = parse_ini_string($file_string);
				if ($file_array === []) {
					return null;
				}

				return $file_array;
			} else {
				throw new \Exception("Can not read the file <br>", E_USER_ERROR);
			}
		}

	/**
	 * @param $full_path
	 * @param $file_name
	 * @param $ext
	 *
	 * @return bool �������� ������� ����� ������ � ����������
	 *
	 * �������� ������� ����� ������ � ����������
	 * @throws \Exception
	 */
	private static function checkClass($full_path, $file_name, $ext)
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

					return false;
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage()."<br>", E_USER_ERROR);
				}
			}

			return true;
		}

	/**
	 * @param $data
	 *
	 * @return bool
	 * �������� ������������� ������ � ����� ���� �, ���� ����, ��������� �����
	 * @throws \Exception
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
						file_put_contents(self::$fileMap, $fileMapWrite, LOCK_EX);
						unset($fileMap);
					}

					return false;
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage()."<br>", E_USER_ERROR);
				}
			}

			// ��������� ������
			return true;
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
				self::$nameSpacesMap[$name_space] = $full_path;

				return true;
			}

			return false;
		}


	/**
	 * @param $class
	 * ������ ���� � ����
	 *
	 * @throws \Exception
	 */
	private static function putFileMap($class)
		{
			try {
				if (self::$isWritable) {
					// ���� ������ � ������ �� �� ����� - �������� ������ � �����
					if (self::checkFileMap($class)) {
						// � ���� �� ���������� - ��������
						file_put_contents(self::$fileMap, $class, FILE_APPEND | LOCK_EX);
					}
				} else {
					throw new \Exception("Can not write contents to an unwritable file '".self::$fileMap."'<br>");
				}
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage()."<br>", E_USER_ERROR);
				}
			}
		}

	/**
	 * @param $data
	 *
	 * ������ ���� � ����
	 *
	 * @throws \Exception
	 */
	private static function putLog($data)
		{
			try {
				$file_path = self::$dirCashe.self::$fileLog;
				$data = ("[ ".$data." => ".date('d.m.Y H:i:s')." ]<br>".PHP_EOL);
				file_put_contents($file_path, $data, FILE_APPEND | LOCK_EX);
			}
			catch (\Exception $e) {
				if (DEBUG_MODE) {
					throw new \Exception("������: ".$e->getMessage()."<br>", E_USER_ERROR);
				}
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
	private static function logFindStart()
		{
			if (DEBUG_MODE) {
				self::putLog(('<br><b style="background-color: #ffffaa;">�������� ����������� �����</b>'));
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
	 * @param $className
	 * @param $namespace
	 * @param $flag
	 */
	private static function findClass($className, $namespace, &$flag)
		{
			foreach (self::$arrauFilesExtensions as $ext) {
				foreach (self::$paths as $path) {

					$full_path = SITE_PATH.str_replace(['\\', '/'], DIRSEP, $path);
					$flag = self::checkClassNameInCash($className, $ext); // �������� ���������� ������ � ���
					if (false === $flag) {
						break;
					}
					$flag = self::checkClass($full_path.$namespace, $className,
											 $ext); // �������� ������ � ������� ����������
					if (false === $flag) {
						break;
					}
					self::logFindStart(); // ��� - ������ �����������
					self::recursiveClassAutoload($full_path, $namespace, $className, $ext,
												 $flag); // ����������� ������������ �����
					if (false === $flag) {
						break;
					}
				}
				if (false === $flag) {
					break;
				}
			}
		}

}

\spl_autoload_register('Core\Autoloader::autoload');