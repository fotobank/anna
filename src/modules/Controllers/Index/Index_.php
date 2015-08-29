<?php
/**
 * контроллер для index
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date:     26.05.2015
 * @time:     1:27
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


/** @noinspection PhpIncludeInspection */
require(SITE_PATH.'inc/carosel.php');

/**==========================для раздела "отзывы"====================*/
if (isset($_POST['nick']) && isset($_POST['email'])) {
	setcookie("nick", $_POST['nick'], time() + 300);
	setcookie("email", $_POST['email'], time() + 300);
}
/**==================================================================*/


try {
	$data = new modules\Models\Index\IndexPage([
								   // свойства Base
								   'file_meta_title' => SITE_PATH.'src/config/meta_title.ini',
								   'admin_mode'      => if_admin(true),
								   // footer
								   'debug_mode'      => DEBUG_MODE,
								   'auto_copyright'  => auto_copyright('2011'),
								   'php_sessid'      => isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : ip(),
								   // свойства IndexPage
								   // 'carousel'     => carousel(), //карусель
								   'http_host'       => getenv('HTTP_HOST'),  // телефон в слайдере
								   'filenews'        => 'news.txt', // файл новостей
								   'lite_box_path'   => 'files/slides/*.jpg' // маска и путь сканирования лайтбокса
							   ]);
	echo $mustache->render('index', $data);

}
catch (Exception $e) {
	echo "Ошибка: ".$e->getMessage();
}