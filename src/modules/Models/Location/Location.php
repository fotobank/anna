<?php
/**
 * Класс PrintError
 * @created   by PhpStorm
 * @package   Error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     17.06.2015
 * @time:     16:21
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Models\Location;

use modules\Models\Base as model;


/**
 * Class Error
 * @package modules\Models\Error
 */
class Location extends model\Base {

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