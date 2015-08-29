<?php
/**
 * ���������� ��� index
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright ��������� ����� (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     1:27
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/** @noinspection PhpIncludeInspection */
require(SITE_PATH.'inc/carosel.php');

/**==========================��� ������� "������"====================*/
if (isset($_POST['nick']) && isset($_POST['email'])) {
	setcookie("nick", $_POST['nick'], time() + 300);
	setcookie("email", $_POST['email'], time() + 300);
}
/**==================================================================*/


try {
	$data = new modules\Models\Index\IndexPage([
								   // �������� Base
								   'file_meta_title' => SITE_PATH.'src/config/meta_title.ini',
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
	echo $mustache->render('index', $data);

}
catch (Exception $e) {
	echo "������: ".$e->getMessage();
}