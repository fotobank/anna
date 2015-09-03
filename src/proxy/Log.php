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
 *
 * @method   static putLog($filepath, $contents)
 * @see      lib\File\Log::putLog()
 *
 * @method   static putEmail()
 * @see      lib\File\Log::putEmail()
 *
 * @method   static getFileLog()
 * @see      lib\File\Log::getFileLog()
 *
 * @method   static writeLog()
 * @see      lib\File\Log::writeLog()
 *
 * @method   static write($entry)
 * @see      lib\File\Log::write()
 *
 * @method   static getLog($logFilename)
 * @see      lib\File\Log::getLog()
 *
 * @method   static load()
 * @see      lib\File\Log::load()
 *
 * @method   static emptyLog()
 * @see      lib\File\Log::emptyLog()
 *
 * @method   static setEmail($email)
 * @see      lib\File\Log::setEmail()
 *
 * @method   static setMaxDir($max_dir)
 * @see      lib\File\Log::setMaxDir()
 *
 * @method   static setInterval($interval)
 * @see      lib\File\Log::setInterval()
 *
 * @method   static setMaxFileSize($max_file_size)
 * @see      lib\File\Log::setMaxFileSize()
 *
 * @method   static isExists()
 * @see      lib\File\Log::isExists()
 *
 * @method   static setGlue($glue)
 * @see      lib\File\Log::setGlue()
 *
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
        $options = Di::get('log');
        $instance->setOptions($options);
        return $instance;
    }

}