<?php
/**
 * ����� ������������ ���
 * @created   by PhpStorm
 * @package   Dumper.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date      :     17.05.2015
 * @time      :     2:58
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace Common\Container;


/**
 * Class Dumper
 * @package Common\Container
 */
trait Dumper
{
	/**
	 * @param $var
	 */
	public function dump($var)
		{
			echo '<pre>'.print_r($var, true).'</pre>';
		}
}