<?php

require( __DIR__ . '/system/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/carosel.php' );
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/file_news.php' );
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/title.php' ); // ����� � �������� ��� ���

header( 'Content-type: text/html; charset=windows-1251' );
/**==========================��� ������� "������"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/


$data = new index\IndexPage();

echo $mustache->render( 'index', $data );

ob_end_flush();