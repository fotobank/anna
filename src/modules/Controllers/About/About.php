<?php
/**
 * ����� Controller About
 * @created   by PhpStorm
 * @package   About.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     18.06.2015
 * @time:     19:35
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace modules\Controllers\About;

use modules\Controllers\Controller\Controller;
use modules\Models\About\About as ModelAbout;


/**
 * Class About
 * @package modules\Controllers\About
 */
class About extends Controller
{

	/**
	 * ������������� ������� Mustache
	 */
	public function __construct()
		{
			parent::init();
		}

	/**
	 *
	 */
	public function about() {
		$model = new ModelAbout();
		return $this->mustache->render('about', $model);
	}
}