<?php
/**
 * Класс Index
 * @created   by PhpStorm
 * @package   Index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     29.05.2015
 * @time:     15:05
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

namespace controllers\Index;

use controllers\Controller\Controller;
use models\Index as model;


/**
 * Class controller_Index
 * @package controllers\Index
 */
class Index extends Controller
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
	 *
	 * @throws \phpbrowscap\Exception
	 */
	public function index() {
		$model = new model\Index([
								// свойства IndexPage
								'http_host'       => getenv('HTTP_HOST'),  // телефон в слайдере
								'filenews'        => 'news.txt', // файл новостей
								'lite_box_path'   => 'files/slides/*.jpg' // маска и путь сканирования лайтбокса
										]);
		echo $this->mustache->render('index', $model);
	}
}