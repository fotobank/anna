<?php
/**
 * Класс предназначен для
 *
 * @created   by PhpStorm
 * @package   DI.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     17.08.2015
 * @time:     13:26
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace classes;


class DI
{
    protected $storage = [];

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->storage[$key]($this);
    }
}