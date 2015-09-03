<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Mustache_register.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright јвторские права (C) 2000-2015, Alex Jurii
 * @date:     26.08.2015
 * @time:     1:28
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/**
 * @namespace
 */
namespace controller\Helper;

use Mustache_Autoloader;
use Mustache_Engine as Mustache;
use proxy\Di;

return
    /**
     * @return Mustache
     */
    function () {
        // mustache
        Mustache_Autoloader::register();
        // инициализаци€ шаблонизатора Mustache
        return new Mustache(Di::get('mustache'));
    };
