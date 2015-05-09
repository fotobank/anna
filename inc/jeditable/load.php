<?php
require (__DIR__ .'/../../system/config/config.php');
header( 'Content-type: text/html; charset=windows-1251' );
header("Cache-Control: no-cache");
if(isset($_GET['renderer'])) {
	$renderer = $_GET['renderer'];
} else if(isset($_POST['renderer'])) {
	$renderer = $_POST['renderer'];
} else {
	$renderer = NULL;
}
if(isset($_GET['id'])) {
	$token = $_GET['id'];
} else if(isset($_POST['id'])) {
	$token = $_POST['id'];
} else {
	$token = NULL;
}

$db->where("teg_id", $token);
$query = $db->getOne("spec_category", 'text');



print $query['text'];