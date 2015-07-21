<?php
/**
 * Класс controllers\Error
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

use controllers\Controller\Controller;
use models\Error as model;


/**
 * Class Error
 * @package controllers\Error
 */
class Error extends Controller {

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

		$model = new model\Error();
		echo $this->mustache->render('404', $model);
	}

	/**
	 * @internal param string $info
	 * @internal param int|string $err
	 */
	public function error424() {

		$model = new model\Error();
		echo $this->mustache->render('424', $model);
	}

}