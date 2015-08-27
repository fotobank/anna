<?php
/**
 * Класс предназначен для
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

namespace controllers\About;

use controllers\Controller\Controller;
use models\About as model;


/**
 * Class About
 * @package controllers\About
 */
class About extends Controller
{

	/**
	 * инициализация вьювера Mustache
	 */
	public function __construct()
		{
			parent::init();
		}

	/**
	 * экшен
	 */
	public function about() {
		$model = new model\About();
		echo $this->mustache->render('about', $model);
	}
}