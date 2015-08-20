<?php
/**
 * Класс controllers\PrintError
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

namespace controllers\Location;

use controllers\Controller\Controller;
use models\Location as model;


/**
 * Class Error
 * @package controllers\Error
 */
class Location extends Controller {

	/**
	 *
	 */
	public function __construct() {
		{
			parent::init();
		}
	}

	/**
	 * error404()
	 */
	public function error404() {

		$model = new model\Location();
		echo $this->mustache->render('error\404', $model);
		exit;
	}

	/**
	 * error403()
	 */
	public function error403() {

		$model = new model\Location();
		echo $this->mustache->render('error\403', $model);
		exit;
	}

	/**
	 * stop()
	 */
	public function stop() {

		$model = new model\Location([
			                         'http_host' => getenv('HTTP_HOST')
		                         ]);
		echo $this->mustache->render('error\stop', $model);
		exit;
	}

}