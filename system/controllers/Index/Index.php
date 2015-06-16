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

use controllers\Controller as controller;
use models\Index as model;


/**
 * Class controller_Index
 * @package controllers\Index
 */
class Index extends controller\Controller
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
								// �������� Base
								'file_meta_title' => SITE_PATH.'system/config/meta_title.ini',
								'admin_mode'      => if_admin(true),
								// footer
								'debug_mode'      => DEBUG_MODE,
								'auto_copyright'  => auto_copyright('2011'),
								'php_sessid'      => isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : ip(),
								// �������� IndexPage
								// 'carousel'     => carousel(), //��������
								'http_host'       => getenv('HTTP_HOST'),  // ������� � ��������
								'filenews'        => 'news.txt', // ���� ��������
								'lite_box_path'   => 'files/slides/*.jpg' // ����� � ���� ������������ ���������
										]);
		echo $this->mustache->render('index', $model);
	}

}