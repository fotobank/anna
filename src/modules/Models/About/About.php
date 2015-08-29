<?php
/**
 * Класс предназначен для 
 * @created   by PhpStorm
 * @package   About.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     30.06.2015
 * @time:     1:14
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\About;

use modules\Models\Base\Base;

/**
 * Class About
 * @package modules\Models\About
 */
class About extends Base
{

	/**
	 * @param $options
	 */
	public function __construct($options = [])
	{
		// настройка свойств класса
		$this->setOptions($options);
		// инициализация конструктора родительского класса
		parent::__construct();

	}

}