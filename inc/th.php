<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.07.14
 * Time: 11:54
 */


$path = isset( $_GET['img'] ) ? $_GET['img'] : FALSE;

if ( $path && preg_match( '#\.(gif|jpeg|jpg|png)$#i', $path ) ) {

	require ( __DIR__ .'/../system/config/config.php');

	if ( CODE_PAGE == 'utf-8' ) $path = iconv( 'windows-1251', 'utf-8', $path );

	$dirname = $basename = '';
	extract(path_info( $path, EXTR_OVERWRITE )); // если переменная существует она будет переписана
	$realpath = SITE_PATH . 'files/portfolio/' . $dirname . "/thumb/" . $basename;

	$image    = @imagecreatefromstring( @file_get_contents( $realpath ) );
	if ( ! $image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( "../images/not_foto.png" ) );
	}
// Send the image
	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );
}