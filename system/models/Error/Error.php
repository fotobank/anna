<?php
/**
 * Класс Error
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

namespace models\Error;

use models\Base as model;
use proxy\Server;

/**
 * Class Error
 * @package models\Error
 */
class Error extends model\Base {

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