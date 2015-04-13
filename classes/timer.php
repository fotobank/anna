<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 18.07.14
 * Time: 2:14
 */

// Измерение времени выполнения скрипта
// найденно на просторах всемирной паутины
/*
 * $timer = new timer();


			$timer->start_timer();

			for($i=0;  $i>10000;  $i++)
			{
				$thumb1 = recursive_dir( $dir, $mask, $ok_subdir );
			}
			$firstTime = $timer->end_timer();
			$t1 = 'Код 1 работал '.$firstTime.'<br/>';

			$timer->start_timer();

			for($i=0;  $i>10000;  $i++)
			{
				$thumb2 = recursive( '/files',  array('thumb' , 'slides', 'rotate_image_news') );
			}

			$secondTime = $timer->end_timer();
			$t2 = 'Код 2 работал '.$secondTime.'<br/>';

			$tt = $firstTime - $secondTime;
 */
set_time_limit(0);

/**
 * Class timer
 */
class timer
{
	private $start_time;

	/**
	 * @return float
	 */
	private function get_time()
	{
		list($usec, $seconds) = explode(" ", microtime());
		return ((float)$usec + (float)$seconds);
	}

	function start_timer()
	{
		$this->start_time = $this->get_time();
}

	function end_timer()
	{
		return ($this->get_time() - $this->start_time);
}
}