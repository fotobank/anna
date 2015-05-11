<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 18.07.14
 * Time: 8:50
 */

class TestTime {
	// Создаем два свойства нашего класса
	private $start;
	// Создаем метод, который ставит начальную точку и переводит ее к виду "сек.микросек"
	/**
	 *
	 */
	public function __construct() {
		set_time_limit(0);
		$this->start = microtime(true);
	}
	// Создаем метод, который подсчитывает сколько прошло времени
	/**
	 * @return string
	 */
	public function finish(){
		return number_format(microtime(true) - $this->start,5);
	}

	/**
	 * @param     $test1
	 * @param     $test2
	 * @param int $test3
	 */
	public function rezult($test1, $test2, $test3 = 0){

		if ($test1 < $test2) {
			$rez_test = ($test2 - $test1)/$test1 * 100;
			echo('<br>Test №1 быстрее Тест №2 на '.round($rez_test,2).'%<br>');
			$this->rezult2($test1, $test3);
		} else {
			$rez_test = ($test1 - $test2)/$test2 * 100;
			echo('<br>Test №2 быстрее Теста №1 на '.round($rez_test,2).'%<br>');
			$this->rezult3($test2, $test3);
		}
	}

	/**
	 * @param $test1
	 * @param $test3
	 */
	public function rezult2($test1, $test3){

		if ($test1 < $test3) {
			$rez_test = ($test3 - $test1)/$test1 * 100;
			echo('Test №1 быстрее Тест №3 на '.round($rez_test,2).'%');
		} else {
			$rez_test = ($test1 - $test3)/$test3 * 100;
			echo('Test №3 быстрее Теста №1 на '.round($rez_test,2).'%');
		}
	}

	/**
	 * @param $test2
	 * @param $test3
	 */
	public function rezult3($test2, $test3){

		if ($test2 < $test3) {
			$rez_test = ($test3 - $test2)/$test2 * 100;
			echo('Test №2 быстрее Тест №3 на '.round($rez_test,2).'%');
		} else {
			$rez_test = ($test2 - $test3)/$test3 * 100;
			echo('Test №3 быстрее Теста №2 на '.round($rez_test,2).'%');
		}
	}
}