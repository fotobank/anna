<?php
session_start();

$browser_cache = 60*60*24*7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)
$id = (int)trim($_GET['id']);
$document_root  = $_SERVER['DOCUMENT_ROOT'];

/* helper function: Send headers and returns an image. */
function sendImage($filename, $browser_cache) {
	$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	if (in_array($extension, array('png', 'gif', 'jpeg'))) {
		header("Content-Type: image/".$extension);
	} else {
		header("Content-Type: image/jpeg");
	}
	header("Cache-Control: private, max-age=".$browser_cache);
	header('Expires: '.gmdate('D, d M Y H:i:s', time()+$browser_cache).' GMT');
	header('Content-Length: '.filesize($filename));
	readfile($filename);
	exit();
}

/* Mobile detection
   NOTE: only used in the event a cookie isn't available. */
function is_mobile() {
	$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
	return strpos($userAgent, 'mobile');
}

/* helper function: Create and send an image with an error message. */
function sendErrorImage($message) {
	/* get all of the required data from the HTTP request */
	$document_root  = $_SERVER['DOCUMENT_ROOT'];
	$requested_uri  = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
	$requested_file = basename($requested_uri);
	$source_file    = $document_root.$requested_uri;

	if(!is_mobile()){
		$is_mobile = "FALSE";
	} else {
		$is_mobile = "TRUE";
	}

	$im            = ImageCreateTrueColor(600, 300);
	$text_color    = ImageColorAllocate($im, 233, 14, 91);
	$message_color = ImageColorAllocate($im, 91, 112, 233);

	ImageString($im, 5, 5, 5, "Error:", $text_color);
	ImageString($im, 3, 5, 25, $message, $message_color);

	ImageString($im, 5, 5, 85, "Potentially useful information:", $text_color);
	ImageString($im, 3, 5, 105, "DOCUMENT ROOT IS: $document_root", $text_color);
	ImageString($im, 3, 5, 125, "REQUESTED URI WAS: $requested_uri", $text_color);
	ImageString($im, 3, 5, 145, "REQUESTED FILE WAS: $requested_file", $text_color);
	ImageString($im, 3, 5, 165, "SOURCE FILE IS: $source_file", $text_color);
	ImageString($im, 3, 5, 185, "DEVICE IS MOBILE? $is_mobile", $text_color);

	header("Cache-Control: no-store");
	header('Expires: '.gmdate('D, d M Y H:i:s', time()-1000).' GMT');
	header('Content-Type: image/jpeg');
	ImageJpeg($im);
	ImageDestroy($im);
	exit();
}



if(isset($_SESSION['class_protect_picture'][$id])) {

$file = $_SESSION['class_protect_picture'][$id];
if (is_readable($document_root.'/'.$file)){
	sendImage($document_root.'/'.$file, $browser_cache);
} else {
	sendErrorImage("No File Copy!!!");
	echo ("Файла не существует или установлен запрет на чтение.");
}
	} else {
	echo ("Фотограия защищена авторским правом! Скачивание и использование фотографии с этого сайта в каких - либо целях недопустимо!
	Если Вам нужна эта фотография - свяжитесь с фотографом.");
}