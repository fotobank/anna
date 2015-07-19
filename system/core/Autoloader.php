<?php
/**
 * ����� ������������ ��� ��������������� �������� �������
 *
 * @created   by PhpStorm
 * @package   https://github.com/fotobank/anna.od.ua/blob/master/system/core/Autoloader.php
 * @version   1.3
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


namespace core;

use exception\BaseException;
use Exception;

/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/exception/IException.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/exception/BaseException.php');


/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class aException extends BaseException
{
}

;

/**
 * Class Autoloader
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
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
        'system',
        'system/controllers',
        'system/models'
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

    // namespace ������
    protected $name_space;

    /**
     * ����������� ������
     */
    public function __construct()
    {
        spl_autoload_extensions('.php');
        /** ��������� ����� ������������ */
        spl_autoload_register(['Core\\Autoloader', 'autoload']);

        /** ��������������� �������  */
        $this->dir_cashe = SITE_PATH . str_replace(['\\', '/'], DS, $this->dir_cashe) . DS;
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
        /** ���� ���� ����� ���������� ���������� - ��������� ��� � ������
         * ����� - ������������� �����, ������� ���� � ��������� � ������  */
        if (false === $this->createFile($this->file_array_scan_files, '')) {
            $this->array_scan_files = $this->arrFromFile($this->file_array_scan_files);
        } else {
            $this->updateScanFiles();
        }
    }

    /**
     * @param $class_name
     * ������������� ������ �������
     *
     * @throws aException
     */
    public function autoload($class_name)
    {
        $this->wrapperTryCatch(function () use ($class_name) {

            $this->name_space = '';
            /** ���������� ����� � ������� � namespace */
            $lastNsPos = strrpos($class_name, '\\');
            if ($lastNsPos) {
                $this->name_space = str_replace(['\\', '/'], DS, substr($class_name, 0, $lastNsPos));
                $class_name = substr($class_name, $lastNsPos + 1);
                $this->findClass($class_name);

                return;
            }
            /** ������� ������ ��� namespace ( ���� namespace ���������� �� ����������� ���������� ) */
            $this->findClass($class_name);

        });
    }


    /**
     * @param  $class_name
     *
     * @return bool
     * @throws aException
     */
    private function findClass($class_name)
    {
        foreach ($this->files_ext as $ext) {

            /** �������� ���������� ������ � ��� */
            if (false === $this->checkClassNameInCash($class_name, $ext)) {
                return false;
            }

            if (false === $this->checkScanFiles($class_name, $ext)) {
                return false;
            }
            /** ��������� log ����� �� ������ */
            $this->logLoadError($class_name);

            throw new aException("����� <b>'{$class_name}'</b> �� ������");
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
     */
    protected function checkClassNameInCash($class_name, $ext)
    {
        return $this->wrapperTryCatch(function () use ($class_name, $ext) {

            $class_name_space = $class_name;

            if (!empty($this->name_space)) {

                $class_name_space = $this->name_space . DS . $class_name;
            }

            if (!empty($this->array_class_cache[$class_name_space])) {

                // �������� �� ������������ ���� � ���� ���� ���� $this->name_space
                // ���� ���� ������������(����� ������� ���������) - �����
                if (!isset($this->array_class_cache[$class_name_space])) {
                    // ������ ���
                    return true;
                }

                $filePath = $this->array_class_cache[$class_name_space] . DS . $class_name . $ext;
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
     * @param $ext
     *
     * �������� ������� $class_name � $array_scan_files
     * ���� ����� �� ������ - �������� ��� � ��������� ��� ���
     *
     * @return bool
     * @throws aException
     */
    private function checkScanFiles($class_name, $ext)
    {
        return $this->wrapperTryCatch(function () use ($class_name, $ext) {

            if (isset($this->array_scan_files[$class_name])) {

                return $this->checkClassNameInBaseScanFiles($class_name, $ext);

            } else {
                /** �������� ���������� � ���� ������������ ������������ */
                $this->updateScanFiles();
                /** ��������� ��� ��� */
                if (isset($this->array_scan_files[$class_name])) {

                    return $this->checkClassNameInBaseScanFiles($class_name, $ext);

                } else {
                    throw new aException('����� <b>"' . $class_name . '"</b> �� ������');
                }
            }
        });
    }

    /**
     * @param $class_name
     * @param $ext
     * @return bool
     */
    function checkClassNameInBaseScanFiles($class_name, $ext)
    {
        /** �������� � namespase */

        if ($this->name_space != '') {
            foreach ($this->paths as $path) {
                $path_class = SITE_PATH . $path . DS . $this->name_space;

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
     * @throws aException
     */
    protected function updateScanFiles()
    {
        foreach ($this->paths as $path) {
            $path = str_replace(['\\', '/'], DS, $path);
            $this->array_scan_files = $this->rScanDir(SITE_PATH . $path . DS);
        }
        $this->arrToFile($this->array_scan_files, $this->file_array_scan_files);
        $this->updateScanFilesLog();
    }

    /**
     * @param string $base
     * ������� ��������� �������� ��������������� ������ ���� ������ � �������� ���������� � ��������������
     * example: echo '<pre>'; var_export(rScanDir(dirname(__FILE__).'/')); echo '</pre>';
     *
     * @param array $data
     *
     * @return array
     */
    private function rScanDir($base = '', &$data = [])
    {
        static $data;
        $this->wrapperTryCatch(function () use ($base, &$data) {
            if (is_dir($base)) {
                $array = array_diff(scandir($base), ['.', '..']);
                foreach ($array as $value) {

                    if (is_dir($base . $value)) {
                        $data = $this->rScanDir($base . $value . DS, $data);

                    } elseif (is_file($base . $value)) {
                        foreach ($this->files_ext as $mask) {
                            $path_parts = pathinfo($value);
                            $extension = isset($path_parts['extension']) ? $path_parts['extension'] : false;
                            if ($mask == '.' . $extension) {
                                $data[$path_parts['filename']][] = rtrim($base, DS);
                            }
                        }
                    }
                }
            } else {
                throw new aException("�� ������� ���������� ������������ ������ <br>");
            }
        });

        return $data;
    }

    /**
     * void arrToFile - ������� ������ ������� � ����
     *
     * @param mixed $value - ������, ������ � �.�.
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
     * @throws aException
     */
    protected function arrFromFile($filename)
    {
        return $this->wrapperTryCatch(function () use ($filename) {
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
            throw new aException("�� ������ ���� ����� '{$filename}' <br>");
        });
    }

    /**
     * ��������� ���������� � ���������� �����
     */
    protected function checkDir()
    {
        $this->wrapperTryCatch(function () {
            if (!is_dir($this->dir_cashe)) {
                mkdir($this->dir_cashe, 0711, true);
            }
            if (!is_writable($this->dir_cashe)) {
                chmod($this->dir_cashe, 0711);
            }
            if (!is_dir($this->dir_cashe) || !is_writable($this->dir_cashe)) {
                throw new aException('can not create "' . $this->dir_cashe . '" an unwritable dir <br>');
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
        return $this->wrapperTryCatch(function () use ($file, $data) {
            if (!file_exists($file)) {
                file_put_contents($file, $data, LOCK_EX);
                if (!file_exists($file)) {
                    throw new aException("can not create '{$file}' an unwritable dir '{$this->dir_cashe}'<br>");
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
     * @throws aException
     */
    private function getFileMap()
    {
        return $this->wrapperTryCatch(function () {

            $file_string = file_get_contents($this->file_array_class_cache);
            if ($file_string === false) {
                throw new aException('Can not read the file <b>"' . $this->file_array_class_cache . '"</b>');
            }

            return parse_ini_string($file_string);
        });

    }

    /**
     * @param $full_path
     * @param $file_name
     * @param $ext
     *
     * @return bool �������� ������������ ������� ����� ������ � ����������
     *
     * �������� ������������ ������� ����� ������ � ����������
     * � ������ ����
     */
    private function checkClass($full_path, $file_name, $ext)
    {
        return $this->wrapperTryCatch(function () use ($full_path, $file_name, $ext) {
            $file = $full_path . DS . $file_name . $ext;
            $file_name = ($this->name_space != '') ? $this->name_space . DS . $file_name : $file_name;
            $this->logFindClass($full_path, $file_name . $ext);
            if (file_exists($file)) {

                /** @noinspection PhpIncludeInspection */
                require_once($file);
                $this->logLoadOk($full_path . DS, $file_name . $ext);
                $this->addNamespace($full_path, $file_name);
                $this->putFileMap($file_name . " = " . $full_path . PHP_EOL);

                return false;
            }

            return true;
        });
    }

    /**
     * @param $full_path
     *
     * @param $file_name
     *
     * @return bool ���������� ���������� ���� ������ � ������
     *
     * ���������� ���������� ���� ������ � ������
     */
    public function addNamespace($full_path, $file_name)
    {
        if (is_dir($full_path)) {
            $this->array_class_cache[$file_name] = $full_path;
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
        $this->wrapperTryCatch(function () use ($class) {
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
     * @throws aException
     */
    private function putLog($data)
    {
        $this->wrapperTryCatch(function () use ($data) {
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
                $full_path . '</b>' . '<b style="color: #ff0000;">' . $file . '</b><br>'));
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
     * @param $closure
     * @return bool
     * @throws Exception
     */
    protected function wrapperTryCatch($closure)
    {
        try {
            return $closure();
        } catch (aException $e) {
            if (DEBUG_MODE) {
                throw $e;
            }
            return false;
        }
    }
}
