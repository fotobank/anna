<?php
/**
 * ����� Index
 * @created   by PhpStorm
 * @package   Index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
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
	 * ������������� ������� Mustache
	 */
	public function __construct()
		{
			parent::init();
		}


	/**
	 * �����
	 *
	 * @throws \phpbrowscap\Exception
	 */
	public function index() {
		$model = new model\Index([
								// �������� IndexPage
								'http_host'       => getenv('HTTP_HOST'),  // ������� � ��������
								'filenews'        => 'news.txt', // ���� ��������
								'lite_box_path'   => 'files/slides/*.jpg' // ����� � ���� ������������ ���������
										]);
		echo $this->mustache->render('index', $model);
	}

	public function run() {
		echo $this();
	}
	/**
	 * Invoke method allows the application to be mounted as a closure.
	 * @param mixed|bool $app parent application that can be referenced by $app->parent
	 * @return mixed|string
	 */
	public function __invoke($app=False) {
		$this->parent = $app;
		return $this->_route($_SERVER['REQUEST_URI']);
	}


	/**
	 * @param $obj
	 * @param int $code
     */
	public function json($obj, $code = 200) {
		header('Content-type: application/json', true, $code);
		echo json_encode($obj);
		exit;
	}

}