<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.07.14
 * Time: 11:54
 */

$path = isset( $_GET['img'] ) ? $_GET['img'] : FALSE;

if ( $path && preg_match( '#\.(gif|jpeg|jpg|png)$#i', $path ) ) {

	include( __DIR__ . '/../inc/func.php' );

	$portolio_dir = "files/portfolio/";



//	if ( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ) $path = iconv( 'windows-1251', 'utf-8', $path );

	if ( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ) $path = cp1251_utf8( $path );




	$realpath = $_SERVER['DOCUMENT_ROOT'] . '/' . $portolio_dir . dirname($path) . "/thumb/" . basename($path);
	$image    = @imagecreatefromstring( @file_get_contents( $realpath ) );
	if ( ! $image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( "../images/not_foto.png" ) );
	}
// Send the image
	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );
}