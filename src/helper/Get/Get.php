<?php
/**
 * Класс helper Get
 *
 * @created   by PhpStorm
 * @package   Get.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:27
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper\Get;


use helper\ArrayHelper\ArrayHelper;


/**
 * Class Server
 */
class Get extends ArrayHelper
{
    /**
     * конструктор
     */
    public function __construct()
    {
        if(null !==$_GET)
        {
            $this->properties = &$_GET;
        }

    }
}