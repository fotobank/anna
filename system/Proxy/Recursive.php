<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Recursive.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     29.07.2015
 * @time:     21:31
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use helper\Recursive as Instance;


/**
 * Class Recursive
 * @package Proxy
 *
 * $type_array (SCAN_DIR_NAME, SCAN_BASE_NAME, SCAN_MULTI_ARRAY, SCAN_CAROUSEL_ARRAY)
 * @method   static array scanDir($base = '', $arr_mask = [], $type_array = SCAN_BASE_NAME,  &$data = [])
 * @see      proxy\Recursive::scanDir()
 *
 * @method   static array recursiveDir($path, $filter = '')
 * @see      proxy\Recursive::recursiveDir()
 *
 * @method   static array recursiveTree($path, $filter = '')
 * @see      proxy\Recursive::recursiveTree()
 *
 * @method   static array recursiveFile($path, $ext = 'php')
 * @see      proxy\Recursive::recursiveFile()
 *
 * @method   static $this setIncDir($inc_dir)
 * @see      proxy\Recursive::setIncDir()
 *
 * @method   static $this setExcDir($exc_dir)
 * @see      proxy\Recursive::setExcDir()
 */
class Recursive extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
    }
}