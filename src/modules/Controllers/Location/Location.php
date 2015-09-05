<?php
/**
 * Класс modules\Controllers\PrintError
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

namespace modules\Controllers\Location;

use modules\Controllers\Controller\Controller;
use modules\Models\Location as model;
use view\View;

/**
 * Class Error
 * @package modules\Controllers\Error
 */
class Location extends Controller {

	/**
	 * инициализация вьювера
	 *
	 * @param \view\View $view
	 *
	 */
	public function __construct(View $view)
	{
		$this->viewer = $view;
	}

	/**
	 * error404()
	 */
	public function error404() {

		try
		{
			$model = new model\Location();
			echo $this->viewer->render('error\404', $model);
			exit;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * error403()
	 */
	public function error403() {

		try
		{
			$model = new model\Location();
			echo $this->viewer->render('error\403', $model);
			exit;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	/**
	 * stop()
	 */
	public function stopPage() {

		try
		{
			$model = new model\Location([
				                            'http_host' => getenv('HTTP_HOST'),
			                            ]);
			echo $this->viewer->render('error\stop', $model);
			exit;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

}