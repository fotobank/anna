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

namespace modules\Controllers\Index;

use modules\Controllers\Controller\Controller;
use modules\Models\Index as model;
use view\View;
use Exception;


/**
 * Class controller_Index
 * @package modules\Controllers\Index
 */
class Index extends Controller
{
	/**
	 * инициализация вьювера
	 *
	 * @param View $view
	 */
	public function __construct(View $view)
		{
			$this->viewer = $view;
		}


	/**
	 * экшен
	 *
	 * @throws \phpbrowscap\Exception
	 */
	public function index() {
		try
		{
			$model = new model\Index([
				                         // свойства IndexPage
				                         'http_host'     => getenv('HTTP_HOST'),  // телефон в слайдере
				                         'filenews'      => 'news.txt', // файл новостей
				                         'lite_box_path' => 'files/slides/*.jpg' // маска и путь сканирования лайтбокса
			                         ]);
			echo $this->viewer->render('index', $model);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}