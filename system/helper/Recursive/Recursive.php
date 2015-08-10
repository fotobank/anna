<?php
/**
 * Класс предназначен для рекурсивного сканирования директорий с фильтром найденных файлов по расширению
 *
 * @created   by PhpStorm
 * @package   Recursive.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.07.2015
 * @time:     17:11
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper;

use exception\HelperException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use FilesystemIterator;


/**
 * Class Recursive
 *
 * @package helper
 */
class Recursive
{
    // включить дирректории
    protected $inc_dir = [];
    // не включать директории
    protected $exc_dir = [];


    /**
     * быстрый метод сканирования директорий
     * @param string $base - закрывающий слеш обязателен  => dir/foto/
     * @param array  $arr_mask - расширение файла без точки
     * @param int    $type_array
     * @param array  $data (SCAN_DIR_NAME, SCAN_BASE_NAME, SCAN_MULTI_ARRAY)
     *
     * @return array
     * @throws \exception\HelperException
     */
    public function scanDir($base = '', $arr_mask = ['jpg', 'png'], $type_array = SCAN_BASE_NAME,  &$data = [])
    {
        static $data;
        if(is_dir($base))
        {
            $array     = array_diff(scandir($base), ['.', '..']);
            $base_name = basename($base);
            foreach($array as $value)
            {
                if(is_dir($base . $value))
                {
                    if(count($this->inc_dir) > 0 && !in_array($value, $this->inc_dir, true))
                    {
                        continue;
                    }
                    if(count($this->exc_dir) > 0 && in_array($value, $this->exc_dir, true))
                    {
                        continue;
                    }
                    $data = $this->scanDir($base . $value . DS, $arr_mask, $type_array, $data);
                }
                else
                {
                    $path_parts = pathinfo($value);
                    $extension  = array_key_exists('extension', $path_parts) ? $path_parts['extension'] : false;
                    if(count($arr_mask) > 0 && !in_array($extension, $arr_mask, true))
                    {
                        continue;
                    }
                    switch($type_array)
                    {
                        case SCAN_DIR_NAME:
                            $data[$base_name][] = $base . $value;
                            break;
                        case SCAN_BASE_NAME:
                            $path_class = preg_replace('#[\\\/]$#', '', $base);
                            $data[$path_parts['filename']][] = $path_class;
                            break;
                        case SCAN_MULTI_ARRAY:
                            $data[] = [$base_name, $base . $value];
                    }
                }
            }
        }
        else
        {
            throw new HelperException('не найдена директория сканирования файлов "' . $base . '"', 404);
        }
        return $data;
    }


    /**
     * @param        $path
     *
     * @param string $filter = '/^.+\.php$/i'
     *
     * @return array рекурсивный перебор
     * рекурсивный перебор
     * с фильтрацией
     * return:
     * [
     * ajaxSite => [
     * 0 => O:\domains\anna.od.ua\classes\ajaxSite\ajaxLoad.php
     * 1 => O:\domains\anna.od.ua\classes\ajaxSite\bodyEdit.php
     * 2 => O:\domains\anna.od.ua\classes\ajaxSite\EditBody.php
     * ]
     * ]
     */
    public function recursiveDir($path, $filter = '')
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator  = new RecursiveIteratorIterator($directory);
        $flags = RecursiveRegexIterator::GET_MATCH;
        $regex     = ('' !== $filter) ? new RegexIterator($iterator, $filter, $flags) : $iterator;
        $filelist  = [];
        foreach($regex as $key => $entry)
        {
            $name_s              = explode(DIRECTORY_SEPARATOR, dirname($key));
            $name_s              = array_pop($name_s);
            $filelist[$name_s][] = $key;
        }

        return $filelist;
    }

    /**
     * выводит массив дерева файлов и каталогов
     * с фильтрацией
     *
     * @param        $path
     * @param string $filter  = '/^.+\.php$/i';
     *
     * @return array
     */
    public function recursiveTree($path, $filter = '')
    {
        $flags =  RecursiveIteratorIterator::CHILD_FIRST;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), $flags);
        $flags = RecursiveRegexIterator::GET_MATCH;
        $regex    = ('' !== $filter) ? new RegexIterator($iterator, $filter, $flags) : $iterator;
        $tree     = [];
        foreach($regex as $splFileInfo)
        {
            for($depth = $iterator->getDepth() - 1; $depth >= 0; $depth--)
            {
                $splFileInfo = [$iterator->getSubIterator($depth)->current()->getFilename() => $splFileInfo];
            }
            $tree = array_merge_recursive($tree, $splFileInfo);
        }
        return $tree;
    }

    /**
     * @param        $path
     * @param string $ext - расширение файла для фильтра
     *
     * file: O:\domains\anna.od.ua\classes\Backup.php
     * return:
     * [
     * Backup => [ 0 => O:\domains\anna.od.ua\classes]
     * ]
     *
     * @return array
     */
    public function recursiveFile($path, $ext = 'php')
    {
        $files = [];
        $flags = FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS;
        $iterator = new RecursiveDirectoryIterator($path, $flags);
        $iterator = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file_info)
        {
            $file_info->getExtension() !== $ext or $files[$file_info->getBaseName('.'.$ext)][] = $file_info->getPath();
        }
        return $files;
    }

    /**
     * @param array $inc_dir
     */
    public function setIncDir($inc_dir)
    {
        $this->inc_dir = $inc_dir;
    }

    /**
     * @param array $exc_dir
     */
    public function setExcDir($exc_dir)
    {
        $this->exc_dir = $exc_dir;
    }
}