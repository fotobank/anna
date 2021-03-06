<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.07.14
 * Time: 11:54
 */

include(__DIR__.'/func.php');
include(__DIR__ . '/../src/config/config.php');

if (!defined('CODE_PAGE')) {
	define( 'CODE_PAGE', detect_encoding(SITE_PATH . 'inc/��������� �������� �������.codepage'));
}

$path = array_key_exists( 'img', $_GET ) ? parse_url($_GET['img'], PHP_URL_PATH) : false;
$realpath = SITE_PATH . 'files/portfolio/';
$image = false;

if ( $path && preg_match( '/\.(gif|jpeg|jpg|png)/i', $path ) ) {

	if (CODE_PAGE === 'utf-8') {
		$path = cp1251_utf8($path);
	}

	$dirname = $basename = '';
	extract(path_info($path));
	$realpath .= $dirname . '/thumb/' . $basename;
	$image = @imagecreatefromstring( @file_get_contents( $realpath ) );
}

	if ( !$image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( '../images/not_foto.png' ) );
	}

	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );