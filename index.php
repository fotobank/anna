<?php

require( __DIR__ . '/inc/config.php' ); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
require( __DIR__ . '/inc/carosel.php' );
require( __DIR__ . '/inc/file_news.php' );
require( __DIR__ . '/inc/title.php' ); // титры в разделах для СЕО

header( 'Content-type: text/html; charset=windows-1251' );
/**==========================для раздела "отзывы"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/

$data = new core_index_page();

echo $mustache->render( 'index', $data );

ob_end_flush();