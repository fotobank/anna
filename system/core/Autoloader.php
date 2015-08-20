<?php
/**
 * ����� ������������ ��� ��������������� �������� �������
 *
 * @created   by PhpStorm
 * @package   https://github.com/fotobank/anna.od.ua/blob/master/system/core/Autoloader.php
 * @version   1.4
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

use Exception;
use exception\BaseException;
use helper\Recursive;
use proxy\Error;


/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/exception/IException.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/exception/BaseException.php');
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system/helper/Recursive/Recursive.php');


/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class AutoloadException extends BaseException
{

}


/**
 * Class Autoloader
 */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
class Autoloader extends Recursive
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
    public $files_ext = ['.php', '.class.php'];

    // ������ ����� ������ ������ �������
    public $paths = ['classes', 'system', 'system/controllers', 'system/models'];

    // ���� ���������
    public $htaccess = '.htaccess';

    // ������ ����� ���������
    public $htaccess_data
        = <<<END
<Files *.html, *.php>
Order deny,allow
Deny from all
</Files>
END;

    // ��� ������������ ���������� ���� � �������� �������
    /**
     * @var array
     */
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
        try
        {
            spl_autoload_extensions('.php');
            /** ��������� ����� ������������ */
            spl_autoload_register(['Core\\Autoloader', 'autoload']);

            /** ��������������� �������  */
            $this->dir_cashe              = SITE_PATH . str_replace(['\\', '/'], DS, $this->dir_cashe) . DS;
            $this->fileLog                = $this->dir_cashe . $this->fileLog;
            $this->file_array_class_cache = $this->dir_cashe . $this->file_array_class_cache;
            $this->file_array_scan_files  = $this->dir_cashe . $this->file_array_scan_files;
            $this->htaccess               = $this->dir_cashe . $this->htaccess;
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
            if(false === $this->createFile(
                    $this->file_array_scan_files, ''
                )
            )
            {
                $this->array_scan_files = $this->arrFromFile($this->file_array_scan_files);
            }
            else
            {
                $this->updateScanFiles();
            }
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }

    /**
     * ��������� ���������� � ���������� �����
     */
    protected function checkDir()
    {
        try
        {
            if(!is_dir($this->dir_cashe))
            {
                mkdir($this->dir_cashe, 0711, true);
            }
            if(!is_writable($this->dir_cashe))
            {
                chmod($this->dir_cashe, 0711);
            }
            if(!is_dir($this->dir_cashe) || !is_writable($this->dir_cashe))
            {
                throw new AutoloadException('can not create "' . $this->dir_cashe . '" an unwritable dir <br>');
            }
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $file
     *
     * ���� ����� ��� - ������� � ���������� �����
     *
     * @param $data
     *
     * @return bool
     * @throws AutoloadException
     */
    protected function createFile($file, $data)
    {
        if(!file_exists($file))
        {
            if(false === file_put_contents($file, $data, LOCK_EX))
            {
                throw new AutoloadException("can not create '{$file}' an unwritable dir '{$this->dir_cashe}'<br>");
            }
            chmod($file, 0600);

            return true;
        }

        return false;
    }

    /**
     * ������ ����� ���� � ������
     *
     * @return array
     * @throws AutoloadException
     */
    private function getFileMap()
    {
        $file_string = file_get_contents($this->file_array_class_cache);
        if($file_string === false)
        {
            throw new AutoloadException('Can not read the file <b>"' . $this->file_array_class_cache . '"</b>');
        }

        return parse_ini_string($file_string);
    }

    /**
     * mixed arrFromFile - ������� �������������� ������ ������� �� �����
     *
     * @param string $filename - ��� ����� ������ ����� ������������� �������������� ������
     *
     * @return mixed
     * @throws AutoloadException
     */
    protected function arrFromFile($filename)
    {
        if(file_exists($filename))
        {
            $file  = file_get_contents($filename);
            $value = unserialize($file);

            if($value === false)
            {
                $this->updateScanFiles();
                $file  = file_get_contents($filename);
                $value = unserialize($file);
            }

            return $value;
        }
        throw new AutoloadException(
            "�� ������ ���� ����� '{$filename}' <br>"
        );
    }

    /**
     * �������� ������� ������ �������� ����������
     * � ����������� �� ����������
     *
     * @throws AutoloadException
     */
    protected function updateScanFiles()
    {
        foreach($this->paths as $path)
        {
            $path = str_replace(['\\', '/'], DS, $path);
            //  $this->array_scan_files = $this->rScanDir(SITE_PATH . $path . DS);
            $this->array_scan_files = $this->scanDir(SITE_PATH . $path . DS, ['php'], SCAN_BASE_NAME);
        }
        $this->arrToFile($this->array_scan_files, $this->file_array_scan_files);
        $this->updateScanFilesLog();
    }


    /**
     * void arrToFile - ������� ������ ������� � ����
     *
     * @param mixed  $value - ������, ������ � �.�.
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
     * ������ � ��� ������������� ���������
     */
    private function updateScanFilesLog()
    {
        try
        {
            if(DEBUG_LOG)
            {
                $this->putLog(
                    ('<br><b style="background-color: #ffffaa;">��������� ���������� � ��������� ���� ������ �������</b>'));
            }
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $data
     *
     * ������ ���� � ����
     *
     * @throws AutoloadException
     */
    private function putLog($data)
    {
        $data = ('[ ' . $data . ' => ' . date('d.m.Y H:i:s') . ' ]<br>'
            . PHP_EOL);
        file_put_contents($this->fileLog, $data, FILE_APPEND | LOCK_EX);
    }

    /**
     * @param $class_name
     * ������������� ������ �������
     *
     * @throws AutoloadException
     */
    public function autoload($class_name)
    {
        $this->name_space = '';
        /** ���������� ����� � ������� � namespace */
        $lastNsPos = strrpos($class_name, '\\');
        if($lastNsPos)
        {
            $this->name_space = str_replace(['\\', '/'], DS, substr($class_name, 0, $lastNsPos));
            $class_name_space = substr($class_name, $lastNsPos + 1);
            if(!$this->findClass($class_name_space))
            {
                return;
            }
        }
        /** ������� ������ ��� namespace ( ���� namespace ���������� �� ����������� ���������� ) */
        $this->findClass($class_name);
    }

    /**
     * @param  $class_name
     *
     * @return bool
     * @throws AutoloadException
     */
    private function findClass($class_name)
    {
        foreach($this->files_ext as $ext)
        {

            /** �������� ���������� ������ � ��� */
            if(false === $this->checkClassNameInCash($class_name, $ext))
            {
                return false;
            }
            if(false === $this->checkScanFiles($class_name, $ext))
            {
                return false;
            }
        }

        return true;
        //    throw new AutoloadException('����� <b>"' . $class_name . '"</b> �� ������');
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
        $class_name_space = $class_name;
        if(!empty($this->name_space))
        {
            $class_name_space = $this->name_space . DS . $class_name;
        }
        if(!empty($this->array_class_cache[$class_name_space]))
        {
            // �������� �� ������������ ���� � ���� ���� ���� $this->name_space
            // ���� ���� ������������(����� ������� ���������) - �����
            if(!isset($this->array_class_cache[$class_name_space]))
            {
                // ������ ���
                return true;
            }
            $filePath = $this->array_class_cache[$class_name_space] . DS
                . $class_name . $ext;
            if(file_exists($filePath))
            {
                /** @noinspection PhpIncludeInspection */
                require_once $filePath;

                return false;
            }
        }

        return true;
    }

    /**
     * @param $class_name
     * @param $ext
     *
     * �������� ������� $class_name � $array_scan_files
     * ���� ����� �� ������ - �������� ��� � ��������� ��� ���
     *
     * @return bool
     * @throws AutoloadException
     */
    private function checkScanFiles($class_name, $ext)
    {
        if(!array_key_exists($class_name, $this->array_scan_files))
        {
            /**
             * ���� �� ������
             * �������� ���������� � ���� ������������ ������������ */
            $this->updateScanFiles();
        }
        if($this->checkClassNameInBaseScanFiles($class_name, $ext))
        {
            // ��������� log ����� �� ������
            $this->logLoadError($class_name . $ext);
            if(DEBUG_MODE)
            {
                throw new AutoloadException('����� <b>"' . $class_name . '"</b> �� ������');
            }
            else
            {
                Error::error404();
            }
        }

        return false;
    }


    /**
     * @param $class_name
     * @param $ext
     *
     * @return bool
     */
    private function checkClassNameInBaseScanFiles($class_name, $ext)
    {
        /** �������� � namespase */

        if($this->name_space != '')
        {
            foreach($this->paths as $path)
            {
                $path_class = SITE_PATH . $path . DS . $this->name_space;
                if(false === $this->checkClass($path_class, $class_name, $ext))
                {
                    // ����� ������
                    return false;
                }
            }
        }
        /** ���� ����� � ���������� namespase */

        if(array_key_exists($class_name, $this->array_scan_files))
        {
            $path_class = $this->array_scan_files[$class_name][0];
            if(false === $this->checkClass($path_class, $class_name, $ext))
            {
                return false;
            }
        }

        // ����� �� ������
        return true;
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
     * @throws AutoloadException
     * @throws Exception
     */
    private function checkClass($full_path, $file_name, $ext)
    {
        try
        {
            $file      = $full_path . DS . $file_name . $ext;
            $file_name = ($this->name_space != '') ? $this->name_space . DS
                . $file_name : $file_name;
            $this->logFindClass($full_path, $file_name . $ext);
            if(file_exists($file))
            {
                /** @noinspection PhpIncludeInspection */
                require_once($file);
                $this->logLoadOk($full_path . DS, $file_name . $ext);
                $this->addNamespace($full_path, $file_name);
                $this->putFileMap($file_name . ' = ' . $full_path . PHP_EOL);

                return false;
            }

            return true;
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $file_path
     * @param $file
     *
     * ������ � ��� ������ ������ �����
     *
     * @throws AutoloadException
     * @throws Exception
     */
    private function logFindClass($file_path, $file)
    {
        if(DEBUG_LOG)
        {
            $this->putLog('���� ���� <b>"' . $file . '"</b> in ' . $file_path);
        }
    }

    /**
     * @param $full_path
     * @param $file
     *
     * ������ ��������� ����������� ������ � ���
     *
     * @throws AutoloadException
     * @throws Exception
     */
    private function logLoadOk($full_path, $file)
    {
        if(DEBUG_LOG)
        {
            $this->putLog(
                ('<br><b style="color: #23a126;">���������� </b> '
                    . '<b style="color: #3a46e1;"> ' .
                    $full_path . '</b>' . '<b style="color: #ff0000;">'
                    . $file
                    . '</b><br>')
            );
        }
    }

    /**
     * @param string $full_path
     *
     * @param string $file_name
     *
     * @return bool ���������� ���������� ���� ������ � ������
     *
     * ���������� ���������� ���� ������ � ������
     */
    public function addNamespace($full_path, $file_name)
    {
        if(is_dir($full_path))
        {
            $this->array_class_cache[$file_name] = $full_path;
        }
    }

    /**
     * @param $class
     *
     * @return bool �������� ������������� ������ � ����� ���� ������� �, ���� ����, ��������� �����
     * �������� ������������� ������ � ����� ���� ������� �, ���� ����, ��������� �����
     * @throws AutoloadException
     * @throws Exception
     * @internal param $data
     */
    private function putFileMap($class)
    {
        try
        {
            $class    = trim($class);
            $file_map = $this->getFileMap();
            list($file_name, $file_patch) = explode('=', $class);
            $file_patch = trim($file_patch);
            $file_name  = trim($file_name);

            if(array_key_exists($file_name, $file_map))
            {
                $full_name_map = $file_name . ' = ' . $file_map[$file_name];
                /** ���� ���� �� ����� */
                if($full_name_map != $class)
                {
                    /** �������� ������ � ������� � �������� ��������� � ���� */
                    $file_map[$file_name] = $file_patch;
                    $file_map_write       = '';
                    foreach($file_map as $drop_name_class => $file)
                    {
                        $file_map_write .= $drop_name_class . ' = ' . $file . PHP_EOL;
                    }
                    /** �������������� ���� */
                    file_put_contents($this->file_array_class_cache, $file_map_write, LOCK_EX);
                    unset($file_map);
                }
            }
            else
            {
                /** ��� �������� ������ */
                file_put_contents($this->file_array_class_cache, $class . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }

    /**
     * @param $class_name
     *
     * @throws AutoloadException
     * @throws Exception
     */
    private function logLoadError($class_name)
    {
        try
        {
            if(DEBUG_LOG)
            {
                $this->putLog('<br><b style="color: #ff0000;">����� "' . $class_name . '" �� ������</b><br>');
            }
        }
        catch(AutoloadException $e)
        {
            throw $e;
        }
    }
}
