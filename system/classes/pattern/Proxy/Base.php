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

namespace classes\pattern\Proxy;

use models\Base\Base as Instance;


/**
 * Class Base
 * @package classes\pattern\Proxy
 */
class Base extends AbstractProxy
{
    /**
     * Base model
     *
     * Example of usage
     *     use classes\pattern\Proxy\Base;
     *
     *     Base::checkClockLockPage($url)
     *
     * @package  Alex\Proxy
     *
     *  показывать только заглавную страницу
     * @method   static boolean setOnluIndex($onluIndex)
     * @see      classes\pattern\Proxy\Base::setOnluIndex()
     *
     * @method   static void checkClockLockPage($url)
     * @see      classes\pattern\Proxy\Base::checkClockLockPage()
     *
     * @method   static mixed getDbTitleName()
     * @see      classes\pattern\Proxy\Base::getDbTitleName()
     *
     * @method   static array globalHeartMenu()
     * @see      classes\pattern\Proxy\Base::globalHeartMenu()
     *
     * @method   static array getMetaTitle()
     * @see      classes\pattern\Proxy\Base::getMetaTitle()
     *
     * @author   Alex Jurii
     * @package  classes\pattern\Proxy
     */
    protected static function initInstance()
    {
        return new Instance();
    }
}