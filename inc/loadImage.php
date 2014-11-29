<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.08.14
 * Time: 23:51
 */


/**
 *
 *
 * Usage
 *
 * Above code would be in a file called image.php.
 *
 * Images would be displayed like this:
 * <img src="<?php loadImage('image.jpg', 50, 50) ?>"
 *
 * @param $url
 * @param $width
 * @param $height
 */
function imageResizer($url, $width, $height) {

	   header('Content-type: image/jpeg');

	   list($width_orig, $height_orig) = getimagesize($url);

	   $ratio_orig = $width_orig/$height_orig;

	   if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
	   } else {
		   $height = $width/$ratio_orig;
	   }

	   // This resamples the image
	   $image_p = imagecreatetruecolor($width, $height);
	   $image = imagecreatefromjpeg($url);
	   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

	   // Output the image
	   imagejpeg($image_p, null, 100);

   }

	//works with both POST and GET
	$method = $_SERVER['REQUEST_METHOD'];

	if ($method == 'GET') {

		imageResizer($_GET['url'], $_GET['w'], $_GET['h']);

	} elseif ($method == 'POST') {

		imageResizer($_POST['url'], $_POST['w'], $_POST['h']);
	}

	// makes the process simpler
	function loadImage($url, $width, $height){
		echo 'image.php?url=', urlencode($url) ,
		'&w=',$width,
		'&h=',$height;
	}