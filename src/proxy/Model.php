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

use modules\Models\Model\Model as Instance;


/**
 * Proxy to Model model
 *
 * Example of usage
 *     use proxy\Model;
 *
 *     Base::checkClockLockPage($url)
 *
 * @package  Alex\Proxy
 *
 *  показывать только заглавную страницу
 * @method   static mixed|void setOnluIndex($onluIndex)
 * @see      proxy\Model::setOnluIndex()
 *
 * @method   static array checkClockLockPage($url)
 * @see      proxy\Model::checkClockLockPage()
 *
 * @method   static mixed getDbTitleName()
 * @see      proxy\Model::getDbTitleName()
 *
 * @method   static array globalHeartMenu()
 * @see      proxy\Model::globalHeartMenu()
 *
 * @method   static bool getMetaTitle()
 * @see      proxy\Model::getMetaTitle()
 *
 * @author   Alex Jurii
 * @package  Proxy
 */
class Model extends AbstractProxy
{
    /**
     * @return \modules\Models\Model\Model
     * @throws \Exception
     */
    protected static function initInstance()
    {
        try
        {
            return new Instance(Config::getInstance());
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }
}