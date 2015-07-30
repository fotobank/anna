<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Profiler.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     28.07.2015
 * @time:     6:08
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace classes\pattern\Proxy;

use admin\applications\Timer\Profiler\Profiler as Instance;


/**
 * ����������:
 *
 * use classes\pattern\Proxy\Profiler;
 * Profiler::setIterataions(1000);
 * Profiler::testFunction('rand',[0,999]);
 * Profiler::generateResults();
 *
 * Class Profiler
 * @package classes\pattern\Proxy
 *
 * @method   static mixed testFunction($functionName, $arguments = [])
 * @see      classes\pattern\Proxy\Profiler::profileFunction()
 *
 * @method   static number testClass($className, $arguments = [])
 * @see      classes\pattern\Proxy\Profiler::profileClass()
 *
 * @method   static number testMethod($object, $methodName, $arguments = [])
 * @see      classes\pattern\Proxy\Profiler::profileMethod()
 *
 * @method   static array getResults()
 * @see      classes\pattern\Proxy\Profiler::getResults()
 *
 * @method   static string generateResults()
 * @see      classes\pattern\Proxy\Profiler::generateResults()
 *
 * @method   static string printResults()
 * @see      classes\pattern\Proxy\Profiler::printResults()
 *
 * @method   static Profiler setIterataions($iterataions)
 * @see      classes\pattern\Proxy\Profiler::setIterataions()
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