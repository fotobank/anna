<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 18.07.14
 * Time: 8:17
 */

include __DIR__ . "/inc/func.php";
include __DIR__ .'/classes/cTimer.php';

/**==========================================1======================================= */

/*$timer = new cTimer();
for ($i=0; $i<1000; $i++) {

	$t1 = recursive_dir( 'files/portfolio/', '*.jpg', array('thumb'), false );

}
echo "Тест 1 пройден за  ".$timer->finish()." секунд <br>";*/
/**==========================================1======================================= */
$timer1 = new cTimer();
$test1 = 0;
for ($i=0; $i<10000; $i++) {


	$str = strip_tags((file_get_contents('inc/passwords.php')));
	preg_match("/\w*\d*\s+=>\s+(?P<host>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<login>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<password>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<db>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<type>[\w\d]*)/i",
		$str, $matches);

	$n = 0;
	while($n < 6) {
		unset($matches[0]);
		$n++;
	}



}
echo "Тест 1 пройден за  ".$timer1->finish()." секунд <br>";
$test1 = $timer1->finish();
/**==========================================2======================================= */
$timer2 = new cTimer();
$test2 = 0;
for ($i=0; $i<10000; $i++) {

	$t3 = getConfig();

}
echo "Тест 2 пройден за ".$timer2->finish()." секунд <br>";
$test2 = $timer2->finish();
/**===========================================3====================================== */
$timer3 = new cTimer();
$test3 = 0;
for ($i=0; $i<10000; $i++) {

	$str = strip_tags((file_get_contents('inc/passwords.php')));
	preg_match("/\w*\d*\s+=>\s+(?P<host>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<login>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<password>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<db>[\w\d]*)\s+
\w*\d*\s+=>\s+(?P<type>[\w\d]*)/i",
		$str, $matches);

	krsort($matches);
	array_splice($matches, 0 ,5);
	unset($matches[0]);

}
echo "Тест 3 пройден за ".$timer3->finish()." секунд <br>";
$test3 = $timer3->finish();
/**===========================================3====================================== */



$timer1->rezult($test1, $test2, $test3);