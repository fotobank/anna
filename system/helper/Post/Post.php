<?php
/**
 * Класс helper Post
 *
 * @created   by PhpStorm
 * @package   Post.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     04.08.2015
 * @time:     0:27
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace helper\Post;

use helper\ArrayHelper\ArrayHelper;


/**
 * Class Server
 */
class Post extends ArrayHelper
{
    /**
     * конструктор
     */
    public function __construct()
    {
        if(null !== $_POST)
        {
            $this->properties = &$_POST;
        }

    }
}