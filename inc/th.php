<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.07.14
 * Time: 11:54
 */


/**
 * @param $path
 *
 * @return mixed|null
 */
function basename_utf8($path) {

	if (strpos($path, '/') !== false)
		return end(explode('/', $path));
	elseif (strpos($path, '\\') !== false)
		return end(explode('\\', $path));
	else
		return null;
}



$path = isset( $_GET['img'] ) ? $_GET['img'] : FALSE;

if ( $path && preg_match( '#\.(gif|jpeg|jpg|png)$#i', $path ) ) {


	$portolio_dir = "files/portfolio/";


	if ( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ) {

		$path = iconv( 'windows-1251', 'utf-8', $path );
		// basename с поддержкой cp1251 для utf-8
		$file_name = ltrim(substr($path, strrpos($path, DIRECTORY_SEPARATOR) ), DIRECTORY_SEPARATOR);

	}  else {

		$file_name = basename($path);
	}

	$file_name = basename_utf8($path);

	$realpath = $_SERVER['DOCUMENT_ROOT'] . '/' . $portolio_dir . dirname($path) . "/thumb/" . $file_name;
	$image    = @imagecreatefromstring( @file_get_contents( $realpath ) );
	if ( ! $image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( "../images/not_foto.png" ) );
	}
// Send the image
	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );
}