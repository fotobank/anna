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
echo "���� 1 ������� ��  ".$timer->finish()." ������ <br>";*/

$id = 'src\class\Boot:start';

define('ITERATION', 1000); // ����������� ���������
/**==========================================1======================================= */
$timer1 = new TestTime();
$test1 = 0;
for ($i=0; $i<ITERATION; $i++) {


	$arr = explode(':', $id);
	$class = reset($arr);



}
$test1 = $timer1->finish();
echo '������������ ��������:', ITERATION, '��.<br>';
echo '���� 1 ������� ��  ', $test1, ' ������; ';
echo '�����, ����������� �� ���� ���������:  ', $test1/ITERATION, ' ������ <br>';
/**==========================================2======================================= */
$timer2 = new TestTime();
$test2 = 0;
for ($i=0; $i<ITERATION; $i++) {

	list($class) = explode(':', $id);

}
$test2 = $timer2->finish();
echo '���� 2 ������� �� ', $test2, ' ������; ';
echo '�����, ����������� �� ���� ���������:  ', $test2/ITERATION, ' ������ <br>';
/**===========================================3====================================== */
$timer3 = new TestTime();
$test3 = 0;
for ($i=0; $i<ITERATION; $i++) {



	$class = (strpos($id, ':') === false) ? $id : strstr($id, ':', true);

}
$test3 = $timer3->finish();
echo '���� 3 ������� �� ',$test3, ' ������; ';
echo '�����, ����������� �� ���� ���������:  ', $test3/ITERATION, ' ������ <br>';
/**===========================================3====================================== */



$timer1->rezult($test1, $test2, $test3);