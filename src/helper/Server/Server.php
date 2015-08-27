<?php
/**
 * Класс Server
 *
 * @created   by PhpStorm
 * @package   Server.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     03.08.2015
 * @time:     19:59
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper\Server;

use helper\ArrayHelper\ArrayHelper;


/**
 * Class Server
 */
class Server extends ArrayHelper
{
    /**
     * конструктор
     */
    public function __construct()
    {
        if(null !==$_SERVER)
        {
            $this->properties = &$_SERVER;
        }

    }
}