<?php
/**
 * Класс предназначен для
 * @created   by PhpStorm
 * @package   Litebox.php
 * @version   1.0
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

	/**
	 *
	 */
	public function __construct()
		{

		}

	/**
	 * @param $dir
	 * @param $img
	 */
	public function thumb($dir, $img)
		{

			$model = new model\Carousel([
											'path' => $dir . DS . $img,
											'real_path' => SITE_PATH . 'files' . DS . 'portfolio' . DS
										]);
			$model->view( true );

		}


	/**
	 * @param $dir
	 * @param $img
	 */
	public function view($dir, $img)
		{

			$model = new model\Carousel([
											'path' =>  $dir . DS . $img,
											'real_path' => SITE_PATH . 'files' . DS . 'portfolio' . DS

										]);
			$model->view();


		}

}