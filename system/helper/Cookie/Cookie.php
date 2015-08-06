<?php
/**
 * Класс helper Cookie
 *
 * @created   by PhpStorm
 * @package   Cookie.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:37
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper\Cookie;

use helper\ArrayHelper\ArrayHelper;


/**
 * Class Server
 */
class Cookie extends ArrayHelper
{
    /**
     * конструктор
     */
    public function __construct()
    {
        if(null !==$_COOKIE)
        {
            $this->properties = &$_COOKIE;
        }

    }

    /**
     * @param $name
     * @param $value
     * @param $expire
     * @param $path
     * @param $domain
     * @param $secure
     *
     * @return $this|void
     */
    public function set($name, $value, $expire  = 0, $path  = '', $domain  = '', $secure  = 0)
    {
        setcookie ($name, $value, $expire, $path, $domain, $secure);
    }

    /**
     * Delete a value from session by its key.
     *
     * @param $name
     *
     * @return bool
     * @internal param $key
     */
    public function del($name)
    {
        setcookie ($name, $value = '');
    }
}