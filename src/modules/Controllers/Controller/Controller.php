<?php
/**
 * Класс Controller
 *
 * @created   by PhpStorm
 * @package   Controller.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     29.05.2015
 * @time      :     14:55
 * @license   MIT License: http://opensource.org/licenses/MIT
 * @formatter:off
 */

namespace modules\Controllers\Controller;

use proxy\Post;
use common\Helper;
use Mustache_Engine as Mustache;


/**
 * Class Controller
 *
 * @package controllers
 * @formatter:off
 *
 * @method Mustache mustacheRegister();
 */
abstract class Controller
{
    use Helper;


    /**
     * @var \Mustache_Engine
     */
    public $mustache;

    /**
     * инициализация класса
     */
    public function init()
    {
        header('Content-type: text/html; charset=windows-1251');

        /**==========================для раздела "отзывы"====================*/
        if(Post::_has('nick') && Post::has('email'))
        {
            setcookie('nick', Post::get('nick'), time() + 300);
            setcookie('email', Post::get('email'), time() + 300);

            setcookie('XDEBUG_SESSION ', 'PHPSTORM', time() + 300);
        }
        /**==================================================================*/

        $this->addHelperPath(__DIR__ . '/Helper/');

        $this->mustache = $this->mustacheRegister();
    }
}