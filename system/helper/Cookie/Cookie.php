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
}