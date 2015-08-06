<?php
/**
 * Класс предназначен для
 *
 * @created   by PhpStorm
 * @package   Error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     06.08.2015
 * @time:     16:47
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use controllers\Error\Error as Instance;

/**
 * Class Error
 *
 * @package proxy
 *
 * @method   static error404()
 * @see      proxy\Profiler::error404()
 *
 * @method   static error403()
 * @see      proxy\Profiler::error403()
 *
 * @method   static stop()
 * @see      proxy\Profiler::stop()
 *
 */
class Error  extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        return $instance;
    }

}