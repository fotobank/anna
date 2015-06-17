<?php
/**
 * Класс предназначен для 
 * @created   by PhpStorm
 * @package   Error.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     05.06.2015
 * @time:     0:53
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Error;

use controllers\Controller as controller;
use models\Index as model;


/**
 * Class Error
 * @package controllers\Error
 */
class Error extends controller\Controller {

	/**
	 *
	 */
	public function __construct() {
		{
			parent::init();
		}
	}

	/**
	 * @internal param string $info
	 * @internal param int|string $err
	 */
	public function error404() {

		header('Location: /404.php', '', 404);
	}

}