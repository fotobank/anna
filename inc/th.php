<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 31.07.14
 * Time: 11:54
 */

include(__DIR__.'/func.php');
include(__DIR__ .'/../system/config/config.php');

if (!defined('CODE_PAGE')) {
	define( 'CODE_PAGE', detect_encoding(SITE_PATH . 'inc/��������� �������� �������.codepage'));
}

$path = isset( $_GET['img'] ) ? $_GET['img'] : false;
$path = parse_url($path, PHP_URL_PATH);

if ( CODE_PAGE == 'utf-8' ) {
	$path = cp1251_utf8($path);
} elseif ( CODE_PAGE == 'windows-1251' ) {
	$path = utf8_cp1251($path);
}

if ( $path && preg_match( '#\.(gif|jpeg|jpg|png)#i', $path ) ) {

	$dirname = $basename = '';
	extract(path_info( $path, EXTR_OVERWRITE )); // ���� ���������� ���������� ��� ����� ����������
	$realpath = SITE_PATH . 'files/portfolio/' . $dirname . "/thumb/" . $basename;

	$image    = @imagecreatefromstring( @file_get_contents( $realpath ) );
	if ( ! $image ) {
		error_log( "\$realpath = " . $realpath . " \$image = " . $image, 0 );
		$image = imagecreatefromstring( file_get_contents( "../images/not_foto.png" ) );
	}

	header( 'Content-type: image/jpeg' );
	imagejpeg( $image, null, 95 );
}