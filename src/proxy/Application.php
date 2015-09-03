<?php
/**
 * Класс Application
 *
 * @created   by PhpStorm
 * @package   Application.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     01.09.2015
 * @time:     12:40
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use application\Application as Instance;

/**
 * Class Application
 *
 * @package proxy
 *
 * @method   static string getEnvironment()
 * @see      application\Application::getEnvironment()
 *
 */
class Application extends AbstractProxy
{

    /**
     * Init instance
     *
     * @return \auth\Auth
     * @throws \Exception
     */
    protected static function initInstance()
    {
        try
        {
            $instance = new Instance();
            return $instance;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}