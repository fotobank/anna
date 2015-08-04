<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Profiler.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     28.07.2015
 * @time:     6:08
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace proxy;

use admin\applications\Timer\Profiler\Profiler as Instance;


/**
 * используем:
 *
 * use proxy\Profiler;
 * Profiler::setIterataions(1000);
 * Profiler::testFunction('rand',[0,999]);
 * Profiler::generateResults();
 *
 * Class Profiler
 * @package Proxy
 *
 * @method   static mixed testFunction($functionName, $arguments = [])
 * @see      proxy\Profiler::profileFunction()
 *
 * @method   static number testClass($className, $arguments = [])
 * @see      proxy\Profiler::profileClass()
 *
 * @method   static number testMethod($object, $methodName, $arguments = [])
 * @see      proxy\Profiler::profileMethod()
 *
 * @method   static array getResults()
 * @see      proxy\Profiler::getResults()
 *
 * @method   static string generateResults()
 * @see      proxy\Profiler::generateResults()
 *
 * @method   static string printResults()
 * @see      proxy\Profiler::printResults()
 *
 * @method   static Profiler setIterataions($iterataions)
 * @see      proxy\Profiler::setIterataions()
 *
 */
class Profiler extends AbstractProxy
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