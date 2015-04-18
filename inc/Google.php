<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 14.07.14
 * Time: 20:15
 */
defined('_SECUR') or die('Доступ запрещен');

$sites  = [ ];
$str    = $tmp = '';
$needle = 'is listed as suspicious';
$path   = 'http://safebrowsing.clients.google.com/safebrowsing/diagnostic?client=googlechrome&hl=en&site=';

$sites[] = 'super-building.do.am';
$sites[] = 'chelsea-best.at.ua';
$sites[] = 'flypictures.do.am';
$sites[] = 'limit-traff.info';
$sites[] = 'kobzarev.com';

$count = count( $sites );

if ( $count > 0 ) {
	$tmp .= '<ul>';
	foreach ( $sites as $site ) {
		$html = file_get_contents( $path . $site );
		$pos  = strpos( $html, $needle );

		if ( $pos === false ) {
			$str   = 'Всё впорядке';
			$color = 'green';
		} else {
			$str   = 'Занесён в чёрный список';
			$color = 'red';
		}

		$tmp .= '<li style="color: ' . $color . '">' . $site . ' &ndash; ' . $str . '</li>';
	}
	$tmp .= '</ul>';
}

echo $tmp;

?>