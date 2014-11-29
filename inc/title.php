<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 16.07.14
 * Time: 9:54
 */
defined('_SECUR') or die('Доступ запрещен');
/**
 * @return array
 */
function title() {
	/** -----------------------------$razdel для фиксации главного меню----------------------------*/
	$razdel = $_SERVER['PHP_SELF'];
	$title  = '';
	if ( $razdel == '/index.php' ) {
		$title = 'Фотограф в Одессе, свадебная фотосъемка | сайт фотографа Алексеева Анна';
		return array( $razdel, $title );
	} else if ( $razdel == '/about.php' ) {
		$title = 'Фотограф в Одессе, свадебная фотосъемка | профессиональный фотограф Алексеева Анна';
		return array( $razdel, $title );
	} else if ( $razdel == '/portfolio.php' ) {
		$title = 'Алексеева Анна | Портфолио';
		return array( $razdel, $title );
	} else if ( $razdel == '/services.php' ) {
		$title = 'Алексеева Анна | Услуги';
		return array( $razdel, $title );
	} else if ( $razdel == '/news.php' ) {
		$title = 'Алексеева Анна | Новости';
		return array( $razdel, $title );
	} else if ( $razdel == '/comments.php' ) {
		$title = 'Алексеева Анна | Контакты';
		return array( $razdel, $title );
	}return array( $razdel, $title );
}