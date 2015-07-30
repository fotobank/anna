<?php
/**
 * Класс предназначен для рекурсивного сканирования директорий с фильтром найденных файлов по расширению
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


/**
 * Class Recursive
 * @package helper
 */
class Recursive
{


    /**
     * поиск во вложенных заданных поддиректориях, если они не заданны - то во всех подпапках
     * $dir = 'files/portfolio/';
     * $inc_subdir = array( 'thumb' ); // в каких поддиректориях искать файлы
     * $mask = '*.jpg'; // маска поиска
     * пример:
     * $thumb = dir( $directory, $mask, $inc_subdir );
     *
     * @param        $dir_scan - папка поиска
     * @param string $mask - маска фильтра по расширению
     * @param array $exc_subdir - какие папки вывода исключить()
     * @param bool $multi_arrau - переключатель вывода в одномерный массив (true - многомерный)
     * @param array $inc_subdir - в каких субдирректориях искать(остальные игнорировать)
     *
     * @return array
     */
    public function dir($dir_scan ='files/portfolio', $mask = '.jpg', $inc_subdir = [], $exc_subdir = [], $multi_arrau = true)
    {
        static $arr_files = [];
        $cont = glob($dir_scan . '/*');
        if(count($cont))
        {
            $name_subdir = basename($dir_scan);
            foreach ($cont as $file)
            {
                if(in_array($name_subdir, $inc_subdir, true) || !count($inc_subdir))
                {
                    if(!in_array($name_subdir, $exc_subdir, true))
                    {
                        if(is_dir($file))
                        {
                            $this->dir($file, $mask, $inc_subdir, $exc_subdir, $multi_arrau);
                        }
                        else
                        {
                            if($mask === '' || strpos($file, $mask) !== false)
                            {
                                if($multi_arrau)
                                {
                                    $arr_files[$name_subdir][] = $file;
                                }
                                else
                                {
                                    $name_dir    = explode('/', $file)[2];
                                    $arr_files[] = [$name_dir, $file];
                                }
                            }
                        }
                    }
                }
                elseif(is_dir($file))
                {
                    $cont_subdir = glob($file . '/*', GLOB_ONLYDIR);
                    if(count($cont_subdir))
                    {
                        foreach ($cont_subdir as $file2)
                        {
                            $this->dir($file2, $mask, $inc_subdir, $exc_subdir, $multi_arrau);
                        }
                    }
                }
            }
        }
        return $arr_files;
    }


    /**
     * @param string $base
     * Функция позволяет получить ассоциированный массив всех файлов в заданной директории и поддиректориях
     * example: echo '<pre>'; var_export(scanDir(dirname(__FILE__).'/')); echo '</pre>';
     *
     * @param string $base - закрывающий слеш обязателен  => dir/foto/
     * @param array $arr_mask - расширение файла без точки
     * @param array $inc_dir
     * @param array $exc_dir
     * @param bool|true $multi_arrau
     * @param array $data
     * @return array
     * @throws HelperException
     */
    public function scanDir($base = '', $arr_mask = ['jpg','png'], $inc_dir = [], $exc_dir = [], $multi_arrau = true, &$data = [])
    {
        static $data;
        if(is_dir($base))
        {
            $base_name = basename($base);
            $array = array_diff(scandir($base), ['.', '..']);
            foreach ($array as $value)
            {
                if(is_dir($base . $value))
                {
                    if(count($inc_dir) > 0 && !in_array($value, $inc_dir, true))
                    {
                        continue;
                    }
                    if(count($exc_dir) > 0 && in_array($value, $exc_dir, true))
                    {
                        continue;
                    }

                    $data = $this->scanDir($base . $value . DS, $arr_mask, $inc_dir, $exc_dir, $multi_arrau, $data);
                }
                else
                {
                $path_parts = pathinfo($value);
                $extension  = array_key_exists('extension', $path_parts) ? $path_parts['extension'] : false;
                if($multi_arrau)
                {
                    if(count($arr_mask) > 0)
                    {
                        if(in_array($extension, $arr_mask, true))
                        {
                            $data[$base_name][] = $base . $value;
                        }
                    }
                    else
                    {
                        $data[$base_name][] = $base . $value;
                    }
                }
                else
                {
                    $name_dir = explode('/', $value)[2];
                    $data[]   = [$name_dir, $base . $value];
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

}
