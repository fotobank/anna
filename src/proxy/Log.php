<?php
/**
 * Класс proxy\Log
 *
 * @created   by PhpStorm
 * @package   Log.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.08.2015
 * @time:     10:49
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use lib\File\Log as Instance;


/**
 * Class Log
 *
 * @package proxy
 */
class Log  extends AbstractProxy
{

    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        $instance->setOptions(Config::getData('log'));
        return $instance;
    }

}