<?php
namespace admin\applications\Timer\Profiler;

use exception\ApplicationException;
use ReflectionClass;


/**
 * * Class Profiler
 *
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
     *
     * @var int
     */
    private $iterataions = 1000;

    /**
     * The results from the various profile stategies used here. This is a
     * stacking result set, meaning this can be used to profile many items, or
     * even compare the results of several.
     *
     * @var array
     */
    private $profileResults = [];

    /**
     * Пример:
     * function myFunction($myInt, $myStr){ echo 'hi'; }
     * $profiler = new admin\applications\Timer\Profiler\Profiler();
     * $timeElapsed = $profiler->testFunction('myFunction', [1, 'test']);
     *
     * @param       $functionName
     * @param array $arguments An optional array of arguments to pass into the class constructor
     *
     * @return mixed number If an argument is of an invalid type or not found
     * param string $functionName The name of the function to profile
     * @throws ApplicationException
     */
    public function testFunction($functionName, $arguments = [])
    {
        if(!function_exists($functionName))
        {
            throw new ApplicationException('Проверяемая функция ' . $functionName . ' не найдена.');
        }

        $iterations = $this->iterataions;
        $this->timerReset();
        for($i = 0; $i < $iterations; $i++)
        {
            call_user_func_array($functionName, $arguments);
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name' => $functionName, 'arguments' => $arguments, 'type' => 'function', 'iterations' => $iterations,
            'time' => $elapsed,
        ];

        return $elapsed;
    }

    /**
     * пример
     *
     * use proxy\Profiler;
     * $directory = 'src/admin';
     *
     * количество итераций
     * Profiler::setIterataions(100);
     *
     * [$directory] - тип массив
     * Profiler::testClass('core\Autoloader',[ [], 'rScanDir', [$directory]]);
     *
     * $dir = new helper\Recursive();
     * Profiler::testMethod($dir, 'dir', [$directory]);
     *
     * Profiler::testClass('helper\Recursive',[[], 'dir', [$directory]]);
     *
     * статичесский класс
     * Recursive::dir('src/admin');
     * вызов
     * Profiler::testClass('proxy\Recursive',[ [], 'dir', [$directory]]);
     *
     * вывод результата
     * Profiler::generateResults();
     *
     * @param       $class_name
     * @param array $arguments
     *
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
            throw new ApplicationException('Класс ' . $class_name .
                                           ' не найден. Проверьте указана ли namespace подключаемого класса?');
        }
        if(!is_array($ac))
        {
            throw new ApplicationException('Тип данных, переданных в $arguments должны быть массивом, А получены - ' .
                                           gettype($ac));
        }
        $iterations = $this->iterataions;
        $this->timerReset();

        $reflected_class = new ReflectionClass($c);
        // если метода нет возможно он статичесский
        if($reflected_class->hasMethod($m) === false)
        {
            for($i = 0; $i < $iterations; $i++)
            {
                $c::$m($am);
            }
        }
        else
        {
            for($i = 0; $i < $iterations; $i++)
            {
                call_user_func_array([new $c($ac), $m], $am);
            }
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name'       => $class_name . '->' . $m, 'arguments' => $ac, 'arguments_method' => $am, 'type' => 'class',
            'iterations' => $iterations, 'time' => $elapsed,
        ];

        return $elapsed;
    }

    /**
     * Пример:
     * class myObject{ public function myMethod($myInt, $myStr){echo 'hi';} }
     * $profiler = new Fogg\Util\Timer\Profiler\Profiler();
     * $myObj = new myObject();
     * $timeElapsed = $profiler->testMethod($myObj, 'myMethod', [1, 'test']);
     *
     * @param object $object An instantiated class object
     * @param string $methodName The name of the method you wish to profile
     * @param array  $arguments An optional array of arguments to be passed into the mehtod
     *
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
        for($i = 0; $i < $iterations; $i++)
        {
            call_user_func_array([$object, $methodName], $arguments);
        }
        $elapsed = $this->timerElapsed();

        $this->profileResults[] = [
            'name' => get_class($object) . '->' . $methodName, 'arguments' => $arguments,
            'type' => 'classMethod', 'iterations' => $iterations, 'time' => $elapsed,
        ];

        return $elapsed;
    }

    /**
     * Получить результаты вашего профиля тестов в формате массива.
     *
     * @return array The profile results in an array format
     */
    public function getResults()
    {
        return $this->profileResults;
    }

    /**
     * Получить результат вашего профиля испытаний в формате HTML.
     *
     * @return string An HTML rendering of the profile results
     */
    public function generateResults()
    {
        $html = '<style>
.profiler{
  font-family: Verdana, Helvetica, sans-serif;
  font-size: 18px;
}
h3 .name {
  color: #ff002b;
}
h2 .count {
  color: #0091b7;
}
h3 .time {
   color: #18951c;
}
h3 .iter {
  color: #6c3bb8;
}
h3 .type {
  color: #0d39d2;
}
h3 .arguments {
  color: #6c3bb8;
  font-size: 16px;
}
fieldset {
width: 800px;
}
</style>';
        $html .= '<h1>Результаты теста:</h1>';
        $html .= "<h2>колличество итераций:  <span class='profiler count'>{$this->profileResults[0]['iterations']}</span></h2>";
        foreach($this->profileResults as $key => $profile)
        {
            $one       = $profile['time'] / $profile['iterations'];
            $one       = sprintf('%.3e', $one);
            $arg_class = '';
            if($profile['arguments'])
            {
                $arg_class .= '<h4><fieldset><legend>аргументы класса:</legend>';
                $arg_class .= '<pre>' . print_r($profile['arguments'], true) . '</pre></fieldset></h4>';
            }
            $arguments_method = '';
            if($profile['arguments_method'])
            {
                if(is_array($profile['arguments_method']))
                {
                    $arguments_method = $this->arrToString($profile['arguments_method']);
                }
                else
                {
                    $arguments_method = $profile['arguments_method'];
                }
            }
            $html .= '<fieldset><legend><h3>[' . ($key + 1) . "]
                тип:  <span class='profiler type'>{$profile['type']}</span>
                вызов: <span class='profiler name'>{$profile['name']}</span>
                <span class='profiler arguments'>{$arguments_method}</span></h3></legend><h3>
                затраченное время: <span class='profiler time'>{$profile['time']}</span><br>
                на одну итерацию: <span class='profiler iter'>{$one}</span><br></h3>";
            $html .= $arg_class;
            $html .= '</fieldset>';
        }
        echo $html;
    }

    /**
     * @param $arr
     *
     * @return string
     */
    protected function arrToString($arr)
    {
        try
        {
            $string = '';
            foreach($arr as $var)
            {
                if(is_array($var))
                {
                    $string .= '["' . implode('","', $var) . '"]';
                }
                else
                {
                    $string .= '"' . $var . '", ';
                }
            }

            return '(' . rtrim($string, ', ') . ')';

        }
        catch(\Exception $e)
        {
            return '(<span style="display: inline-table;">' . print_r($arr, true) . '</span>)';
        }
    }


    /**
     * вывод результата в виде подсвеченной строки
     *
     * @return string
     */
    public function printResults()
    {
        echo '<h1>Результаты теста:</h1>';
        ob_start();
        var_export($this->getResults());
        $data = ob_get_contents();
        ob_end_clean();
        echo highlight_string('<?' . PHP_EOL . $data);
    }

    /**
     * Установить колличество операций
     *
     * @param int $iterations
     *
     * @return Profiler
     * @throws ApplicationException
     */
    public function setIterataions($iterations)
    {
        if(is_numeric($iterations))
        {
            $this->iterataions = $iterations;
        }
        else
        {
            throw new ApplicationException('The passed in $iterations variable must be an integer, ' .
                                           gettype($iterations) . ' given.');
        }
    }

}