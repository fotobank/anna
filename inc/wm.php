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

	if ( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ) $path = iconv( 'windows-1251', 'utf-8', $path );

	$realpath = $_SERVER['DOCUMENT_ROOT'] . '/' . $portolio_dir . $path;
	$image    = @imagecreatefromstring( @file_get_contents( $realpath ) );
	if ( ! $image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( "../images/not_foto.png" ) );
	} else {
		$w = imagesx( $image );
		$h = imagesy( $image );

		$watermark = imagecreatefrompng( '../images/watermark.png' );
		$ww        = imagesx( $watermark );
		$wh        = imagesy( $watermark );

// вставить watermark в правый нижний угол
		imagecopy( $image, $watermark, $w - $ww, $h - $wh, 0, 0, $ww, $wh );

// ... или по центру
// imagecopy($image, $watermark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);
	}
// Send the image
	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );
}