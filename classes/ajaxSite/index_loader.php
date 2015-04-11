<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 17.08.14
 * Time: 2:11
 */


require_once (__DIR__ .'/../../inc/config.php');
header( 'Content-type: text/html; charset=windows-1251' );

if(isset($_GET['id'])) {
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else if(isset($_POST['id'])) {
	$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
	$id = false;
}

if(isset($_GET['location'])) {
	$location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
} else if(isset($_POST['location'])) {
	$location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
} else {
	$location = false;
}


	switch($location)
	{
		case 'index':
			$table = 'index_body';
//			ajaxText_Edit::load($table);
			break;
		case 'about':
			$table = 'spec_about';
			break;
		case 'portfolio':
			$table = 'spec_portfolio';
			break;
		case 'services':
			$table = 'spec_services';
			break;
		case 'news':
			$table = 'spec_news';
			break;
		case 'comments':
			$table = 'spec_comments';
			break;
		default:
			$table = false;
	}


if($id && $table) {

$db->where("id", $id);
$query = $db->getOne($table, 'text');



	if(count($query)) {

	print $query['text'];

	} else {

		echo('������ �������.'.$id.$table);
	}

} else {

	echo ('Err::ajax: "������������ ������� ������."' .$id.$table);

}