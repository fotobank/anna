<?php

require( __DIR__ . '/system/config/config.php'); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/carosel.php' );
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/title.php' ); // титры в разделах для СЕО

header( 'Content-type: text/html; charset=windows-1251' );
/**==========================для раздела "отзывы"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/

use Web\Index as init;

$data = new init\IndexPage();

 echo $mustache->render( 'index', $data );

ob_end_flush();