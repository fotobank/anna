<?php
namespace admin\applications\Timer\Profiler;

use exception\ApplicationException;
use ReflectionClass;


/**
 * * Class Profiler
 * @package admin\applications\Timer\Profiler
 *
 * @method   timer_reset()
 * @method   timer_elapsed()
 *
 */
class Profiler
{

    use TimerTrait;

    /**
     * The number of times to test each function/class/method. Defaults to 1,000.
     * @var int
     */
    private $iterataions = 1000;

    /**
     * The results from the various profile stategies used here. This is a
     * stacking result set, meaning this can be used to profile many items, or
     * even compare the results of several.
     * @var array
     */
    private $profileResults = [];

    /**
     * ������:
     * function myFunction($myInt, $myStr){ echo 'hi'; }
     * $profiler = new admin\applications\Timer\Profiler\Profiler();
     * $timeElapsed = $profiler->testFunction('myFunction', [1, 'test']);
     *
     * @param $functionName
     * @param array $arguments An optional array of arguments to pass into the class constructor
     * @return mixed number If an argument is of an invalid type or not found
     * param string $functionName The name of the function to profile
     * @throws ApplicationException
     */
    public function testFunction($functionName, $arguments = [])
    {
        if(!function_exists($functionName))
        {
            throw new ApplicationException('����������� ������� ' . $functionName . ' �� �������.');
        }

        $iterations = $this->iterataions;
        $this->timerReset();
        for ($i = 0; $i < $iterations; $i++)
        {
            call_user_func_array($functionName, $arguments);
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name' => $functionName, 'arguments' => $arguments, 'type' => 'function', 'iterations' => $iterations,
            'time' => $elapsed
        ];

        return $elapsed;
    }

    /**
     * ������
     *
     * use classes\pattern\Proxy\Profiler;
     * $directory = 'system/admin';
     *
     * ���������� ��������
     * Profiler::setIterataions(100);
     *
     * [$directory] - ��� ������
     * Profiler::testClass('core\Autoloader',[ [], 'rScanDir', [$directory]]);
     *
     * $dir = new helper\Recursive();
     * Profiler::testMethod($dir, 'dir', [$directory]);
     *
     * Profiler::testClass('helper\Recursive',[[], 'dir', [$directory]]);
     *
     * ������������ �����
     * Recursive::dir('system/admin');
     * �����
     * Profiler::testClass('classes\pattern\Proxy\Recursive',[ [], 'dir', [$directory]]);
     *
     * ����� ����������
     * Profiler::generateResults();
     *
     * @param $class_name
     * @param array $arguments
     * @return number If an argument is of an invalid type or not found
     * @throws ApplicationException
     * @internal param array $arguments_class
     * @internal param null $methodName
     * @internal param null $arguments_method
     * @internal param string $className The fully qualified class name
     * @internal param array $arguments An optional array of arguments to pass into the class constructor
     */
    public function testClass($class_name, $arguments = [])
    {
        // $ac - abreviating the array for the sake of brevity
        // $c - abreviating the className for the sake of brevity
        $c = $class_name;
        list($ac, $m, $am) = $arguments;

        if(!class_exists($class_name))
        {
            throw new ApplicationException('����� ' . $class_name .
                                           ' �� ������. ��������� ������� �� namespace ������������� ������?');
        }
        if(!is_array($ac))
        {
            throw new ApplicationException('��� ������, ���������� � $arguments ������ ���� ��������, � �������� - ' .
                                           gettype($ac));
        }
        $iterations = $this->iterataions;
        $this->timerReset();

        $reflected_class = new ReflectionClass($c);
        // ���� ������ ��� �������� �� ������������
        if($reflected_class->hasMethod($m) === false)
        {
            $arg = (count($am) === 1) ? $am[0] : $am;

            for ($i = 0; $i < $iterations; $i++)
            {
                    $c::$m($arg);
            }
        } else
        {
            for ($i = 0; $i < $iterations; $i++)
            {
                call_user_func_array([new $c($ac), $m], $am);
            }
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name' => $class_name . '->' . $m . '()', 'arguments' => $ac, 'arguments_method' => $am, 'type' => 'class',
            'iterations' => $iterations, 'time' => $elapsed
        ];

        return $elapsed;
    }

    /**
     * ������:
     * class myObject{ public function myMethod($myInt, $myStr){echo 'hi';} }
     * $profiler = new Fogg\Util\Timer\Profiler\Profiler();
     * $myObj = new myObject();
     * $timeElapsed = $profiler->testMethod($myObj, 'myMethod', [1, 'test']);
     *
     * @param object $object An instantiated class object
     * @param string $methodName The name of the method you wish to profile
     * @param array $arguments An optional array of arguments to be passed into the mehtod
     * @return number If an argument is of an invalid type or not found
     * @throws ApplicationException
     */
    public function testMethod($object, $methodName, $arguments = [])
    {
        if(!is_object($object))
        {
            throw new ApplicationException('The passed in $object must be an object, ' . gettype($object) .
                                           ' was given instead.');
        }
        if(!is_string($methodName))
        {
            throw new ApplicationException('The passed in $methodName must be a string, ' . gettype($methodName) .
                                           ' was given instead.');
        }
        if(!method_exists($object, $methodName))
        {
            throw new ApplicationException('The passed in $methodName is not a method in the passed in $object');
        }
        if(!is_array($arguments))
        {
            throw new ApplicationException('The passed in $arguments must be an array, ' . gettype($arguments) .
                                           ' was given instead.');
        }

        $iterations = $this->iterataions;
        $this->timerReset();
        for ($i = 0; $i < $iterations; $i++)
        {
            call_user_func_array([$object, $methodName], $arguments);
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name' => get_class($object) . '->' . $methodName, 'arguments' => $arguments, 'type' => 'classMethod',
            'iterations' => $iterations, 'time' => $elapsed
        ];

        return $elapsed;
    }

    /**
     * �������� ���������� ������ ������� ������ � ������� �������.
     *
     * @return array The profile results in an array format
     */
    public function getResults()
    {
        return $this->profileResults;
    }

    /**
     * �������� ��������� ������ ������� ��������� � ������� HTML.
     *
     * @return string An HTML rendering of the profile results
     */
    public function generateResults()
    {
        $html = '<h1>���������� �����:</h1>';

        foreach ($this->profileResults as $profile)
        {
            $one = $profile['time'] / $profile['iterations'];
            $one = sprintf('%.3e', $one);
            $html .= "<h3>
                ��������: <span style='color: #ff002b'>{$profile['name']}</span>
                ���:  <span style='color: #0d43ff'>{$profile['type']}</span>
                ����������� ��������:  <span style='color: #00a0c8'>{$profile['iterations']}</span><br>
                ����������� �����: <span style='color: #d10026'>{$profile['time']}</span><br>
                �� ���� ��������: <span style='color: #d10026'>{$one}</span><br>
                ��������� ������:</h3>";
            $html .= '<pre>' . print_r($profile['arguments'], true) . "</pre>\n";
            if(isset($profile['arguments_method']))
            {
                if(is_array($profile['arguments_method']))
                {
                    $arguments_method = sprintf(implode(',', $profile['arguments_method']));
                } else
                {
                    $arguments_method = $profile['arguments_method'];
                }

                $html .= '<h3>���������: �������:</h3>';
                $html .= '<pre>' . $arguments_method . "</pre>\n";
            }
        }
        echo $html;
    }

    /**
     * ����� ���������� � ���� ������������ ������
     * @return string
     */
    public function printResults()
    {
        echo '<h1>���������� �����:</h1>';
        ob_start();
        var_export($this->getResults());
        $data = ob_get_contents();
        ob_end_clean();
        echo highlight_string('<?' . PHP_EOL . $data);
    }

    /**
     * ���������� ����������� ��������
     *
     * @param int $iterations
     * @return Profiler
     * @throws ApplicationException
     */
    public function setIterataions($iterations)
    {
        if(is_numeric($iterations))
        {
            $this->iterataions = $iterations;
        } else
        {
            throw new ApplicationException('The passed in $iterations variable must be an integer, ' .
                                           gettype($iterations) . ' given.');
        }
    }

}