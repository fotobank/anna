<?php

require(__DIR__.
		'/system/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������
/** @noinspection PhpIncludeInspection */
require(SITE_PATH.'inc/carosel.php');

/**==========================��� ������� "������"====================*/
if (isset($_POST['nick']) && isset($_POST['email'])) {
	setcookie("nick", $_POST['nick'], time() + 300);
	setcookie("email", $_POST['email'], time() + 300);
}
/**==================================================================*/

use Web\Index as init;

try {
	$data = new init\IndexPage([
								   // �������� Base
								   'file_meta_title'  => SITE_PATH.'system/config/meta_title.ini',
								   'admin_mode'     => if_admin(true),
								   // footer
								   'debug_mode'     => DEBUG_MODE,
								   'auto_copyright' => auto_copyright('2011'),
								   'php_sessid'     => isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : ip(),
								   // �������� IndexPage
								   'carousel'       => carousel(), //��������
								   'http_host'      => getenv('HTTP_HOST'),  // ������� � ��������
								   'filenews' => 'news.txt', // ���� ��������
								   'lite_box_path' => 'files/slides/*.jpg' // ����� � ���� ������������ ���������
							   ]);
	echo $mustache->render('index', $data);

}
catch (Exception $e) {
	echo "������: ".$e->getMessage();
}

ob_end_flush();