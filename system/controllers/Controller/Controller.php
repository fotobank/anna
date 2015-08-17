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

namespace controllers\Controller;

use Mustache_Autoloader;
use Mustache_Engine as Mustache;
use Mustache_Loader_FilesystemLoader as Loader;
use Mustache_Logger_StreamLogger as Logger;
use proxy\Post;


/**
 * Class Controller
 *
 * @package controllers
 * @formatter:off
 */
abstract class Controller
{
    const MUSTACHE_TEMPLATES = 'system/views/Mustache/templates';
    const MUSTACHE_PARTIALS  = 'system/views/Mustache/templates/partials';
    const MUSTACHE_CACHE     = 'cache/mustache';
    const MUSTACHE_LOG       = 'log';

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
        }
        /**==================================================================*/

        // mustache
        /** @noinspection PhpIncludeInspection */
        include(SITE_PATH . 'vendor/autoload.php');
        Mustache_Autoloader::register();
        // инициализация шаблонизатора Mustache
        $this->mustache = new Mustache([
                                           // 'template_class_prefix' => '__MyTemplates_',
                                           'cache'                  => (SITE_PATH . Controller::MUSTACHE_CACHE),
                                           'cache_file_mode'        => 0666,
                                           // Please, configure your umask instead of doing this :)
                                           'cache_lambda_templates' => true, 'loader' => new Loader(SITE_PATH
                                                                                                    . Controller::MUSTACHE_TEMPLATES),
                                           'partials_loader'        => new Loader(SITE_PATH
                                                                                  . Controller::MUSTACHE_PARTIALS),
                                           // 'helpers' => [ 'i18n' => function($text) {  } ],
                                           'escape'                 => function ($value)
                                           {
                                               return htmlspecialchars($value, ENT_COMPAT, 'windows-1251');
                                           },
                                           'charset'                => 'windows-1251',
                                           'logger'                 => new Logger(SITE_PATH . Controller::MUSTACHE_LOG),
                                           'strict_callables'       => true, 'pragmas' => [Mustache::PRAGMA_FILTERS],
                                       ]);

    }
}