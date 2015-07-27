<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 17.08.14
 * Time: 2:11
 */

use classes\pattern\Proxy\Db as Db;

/** @noinspection PhpIncludeInspection */
include(__DIR__ .  DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR.'system'.
									   DIRECTORY_SEPARATOR.'config'. DIRECTORY_SEPARATOR . 'config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');


if(isset($_GET['id'])) {
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else if(isset($_POST['id'])) {
	$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
	$id = false;
}

if(isset($_GET['location'])) {
	$location = filter_var($_GET['location'], FILTER_SANITIZE_STRING);
} else if(isset($_POST['location'])) {
	$location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
} else {
	$location = false;
}


	switch($location)
	{
		case 'index' || '/':
			$table = 'index_body';
//			ajaxText_Edit::load($table);
			break;
		case 'about':
			$table = 'about_body';
			break;
		case 'portfolio':
			$table = 'portfolio_body';
			break;
		case 'services':
			$table = 'services_body';
			break;
		case 'news':
			$table = 'news_body';
			break;
		case 'comments':
			$table = 'comments_body';
			break;
		default:
			$table = false;
	}


if($id && $table) {

	Db::where('id', $id);
$query = Db::getOne($table, 'text');



	if(count($query)) {

	print $query['text'];

	} else {

		echo('Статья удалена.'.$id.$table);
	}

} else {

	echo ('Err::ajax: "Неправильные входные данные."' .$id.$table);

}