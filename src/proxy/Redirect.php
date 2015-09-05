<?php
/**
 * Framework Component
 *
 * @created   by PhpStorm
 * @package   Redirect.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     05.09.2015
 * @time:     15:14
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use modules\Controllers\Redirect\Redirect as Instance;


/**
 * Class Redirect
 *
 * @package proxy
 *
 * @method   static url($url = 'index', $code = '302')
 * @see      proxy\Redirect::url()
 *
 */
class Redirect extends AbstractProxy
{

    /**
     * Init instance
     *
     * @return \modules\Controllers\Redirect\Redirect
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