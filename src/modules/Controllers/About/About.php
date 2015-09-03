<?php
/**
 * Класс Controller About
 * @created   by PhpStorm
 * @package   About.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     18.06.2015
 * @time:     19:35
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\About;

use modules\Controllers\Controller\Controller;
use modules\Models\About\About as ModelAbout;
use view\View;


/**
 * Class About
 * @package modules\Controllers\About
 */
class About extends Controller
{

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
	 *
	 */
	public function about() {
		$model = new ModelAbout();
		return $this->viewer->render('about', $model);
	}
}