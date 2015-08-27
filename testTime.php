<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 18.07.14
 * Time: 8:17
 */

include __DIR__ . '/inc/func.php';
include __DIR__ .'/classes/testTime.php';

/**==========================================0======================================= */

/*$timer = new cTimer();
for ($i=0; $i<1000; $i++) {

	$t1 = recursive_dir( 'files/portfolio/', '*.jpg', array('thumb'), false );

}
echo "Тест 1 пройден за  ".$timer->finish()." секунд <br>";*/

$id = 'src\class\Boot:start';

define('ITERATION', 1000); // колличество иттераций
/**==========================================1======================================= */
$timer1 = new TestTime();
$test1 = 0;
for ($i=0; $i<ITERATION; $i++) {


	$arr = explode(':', $id);
	$class = reset($arr);



}
$test1 = $timer1->finish();
echo 'Колличесство итерации:', ITERATION, 'шт.<br>';
echo 'Тест 1 пройден за  ', $test1, ' секунд; ';
echo 'Время, затраченное на одну иттерацию:  ', $test1/ITERATION, ' секунд <br>';
/**==========================================2======================================= */
$timer2 = new TestTime();
$test2 = 0;
for ($i=0; $i<ITERATION; $i++) {

	list($class) = explode(':', $id);

}
$test2 = $timer2->finish();
echo 'Тест 2 пройден за ', $test2, ' секунд; ';
echo 'Время, затраченное на одну иттерацию:  ', $test2/ITERATION, ' секунд <br>';
/**===========================================3====================================== */
$timer3 = new TestTime();
$test3 = 0;
for ($i=0; $i<ITERATION; $i++) {



	$class = (strpos($id, ':') === false) ? $id : strstr($id, ':', true);

}
$test3 = $timer3->finish();
echo 'Тест 3 пройден за ',$test3, ' секунд; ';
echo 'Время, затраченное на одну иттерацию:  ', $test3/ITERATION, ' секунд <br>';
/**===========================================3====================================== */



$timer1->rezult($test1, $test2, $test3);