<?php
/**
 *
 * @created   by PhpStorm
 * @package   Litebox.php
 * @version   1.1
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     18.06.2015
 * @time      :     2:39
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Carousel;

use models\Carousel as model;


/**
 * Class Litebox
 * @package controllers\Litebox
 */
class Carousel
{
	protected $model;

	/**
	 *
	 */
	public function __construct()
		{

			$this->model = new model\Carousel();
		}

	/**
	 * @param $dir
	 * @param $img
	 */
	public function thumb($dir, $img)
		{
			// если задан 'thumb' - выводятся превьюшки без водяного знака
			$this->model->view( $dir . DS . $img, 'thumb' );
		}


	/**
	 * @param $dir
	 * @param $img
	 */
	public function view($dir, $img)
		{
			$this->model->view( $dir . DS . $img );
		}

}