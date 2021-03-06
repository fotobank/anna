<?php
/**
 * ����� ������������ ���
 *
 * @created   by PhpStorm
 * @package   Error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     06.08.2015
 * @time:     16:47
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use modules\Controllers\Location\Location as Instance;

/**
 * Class Error
 *
 * @package proxy
 *
 * @method   static error404()
 * @see      proxy\Location::error404()
 *
 * @method   static error403()
 * @see      proxy\Location::error403()
 *
 * @method   static stopPage()
 * @see      proxy\Location::stop()
 *
 */
class Location extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return \modules\Controllers\Location\Location
     * @throws \Exception
     */
    protected static function initInstance()
    {
        try
        {
            $instance = new Instance(View::getInstance());

            return $instance;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

}