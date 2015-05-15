<?php

require( __DIR__ . '/system/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������
/** @noinspection PhpIncludeInspection */
require( SITE_PATH . 'inc/carosel.php' );

/**==========================��� ������� "������"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/

use Web\Index as init;

try {
	$data = new init\IndexPage([
								   'carousel' => carousel()
							   ]
							   );
	echo $mustache->render( 'index', $data );

} catch (Exception $e) {
	echo "������: " . $e->getMessage();
}



ob_end_flush();