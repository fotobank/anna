<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Base.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     27.07.2015
 * @time:     9:10
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use models\Base\Base as Instance;


/**
 * Proxy to Base model
 *
 * Example of usage
 *     use proxy\Base;
 *
 *     Base::checkClockLockPage($url)
 *
 * @package  Alex\Proxy
 *
 *  показывать только заглавную страницу
 * @method   static mixed|void setOnluIndex($onluIndex)
 * @see      proxy\Base::setOnluIndex()
 *
 * @method   static array checkClockLockPage($url)
 * @see      proxy\Base::checkClockLockPage()
 *
 * @method   static mixed getDbTitleName()
 * @see      proxy\Base::getDbTitleName()
 *
 * @method   static array globalHeartMenu()
 * @see      proxy\Base::globalHeartMenu()
 *
 * @method   static bool getMetaTitle()
 * @see      proxy\Base::getMetaTitle()
 *
 * @author   Alex Jurii
 * @package  Proxy
 */
class Base extends AbstractProxy
{
    /**
     * @return Instance
     */
    protected static function initInstance()
    {
        return new Instance();
    }
}