<?php
/*
  Todo    - шапка проекта
  @author - Jurii
  @date   - 03.04.2015
  @time   - 8:43
*/

defined( '_SECUR' ) or die( 'Доступ запрещен' );
header( 'Content-type: text/html; charset=windows-1251' );
/**==========================для раздела "отзывы"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/
include( __DIR__ . '/title.php' ); // титры в разделах для СЕО
list( $razdel, $title ) = title();

?>
<!DOCTYPE html>
<html lang="ru" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title><?= $title ?></title>
<!--	<meta charset="windows-1251">-->
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="<?= $title ?>" name="author">
	<meta content="<?= $title ?>" name="keywords">
	<meta content="<?= $title ?>" name="description">
	<link href='http://fonts.googleapis.com/css?family=Marck+Script&amp;subset=cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Bad+Script&amp;subset=cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lora:400&amp;subset=cyrillic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="/images/favicon.png" type="image/png">


	<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/ie.css">
	<script src="/js/if_IE/html5.js"></script>
	<![endif]-->


	<?

	if ( $_SERVER['PHP_SELF'] == '/portfolio.php' ) {

		?>
	<link rel='stylesheet' type='text/css' media='screen' href='/css/skin-2.css'>


		<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>
		<script type='text/javascript' src='/js/jquery.easing.1.3.js'></script>

		<script type='text/javascript' src='/js/native/jquery.mb.browser.min.js'></script>
		<script type='text/javascript' src='/js/jquery.jcarousel.min.js'></script>

		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.min.js'></script>


		<script type="text/javascript" src="/js/minified/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox-thumbs.js"></script>


		<script type='text/javascript' src='/js/tabs.js'></script>
		<script type='text/javascript' src='/js/portfolio.js'></script>
	<?

	} else if ($_SERVER['PHP_SELF'] == '/index.php') {

	?>

		<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>
		<script type='text/javascript' src='/js/jquery.easing.1.3.js'></script>
		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.min.js'></script>
		<script type="text/javascript" src="/js/minified/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
		<script type='text/javascript' src='/js/minified/index.who.is.online.min.js'></script>
		<script type='text/javascript' src='/js/minified/ajax.tabs.min.js'></script>
		<script type='text/javascript' src='/js/minified/index.slider.min.js'></script>
		<script type="text/javascript" src="/js/jquery-contained-sticky-scroll.js"></script>

	<?

	} else {

	?>
		<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>
		<script type='text/javascript' src='/js/native/jquery.mb.browser.min.js'></script>
		<script type='text/javascript' src='/js/jquery.easing.1.3.js'></script>
		<script type='text/javascript' src='/js/functions.js'></script>
	<?

	}

	if ( if_admin( $script = "

    <script type='text/javascript' src='/js/jquery-ui.min.js'></script>
	<!-- <script type='text/javascript' src='/js/jeditable/jquery.jeditable.js'></script> -->
	<!--<script src='/js/native/alloy-3.0.1/build/aui/aui-min.js'></script> -->

	<script src='/js/jqueryui-editable/js/jqueryui-editable.js'></script>
	<script type='text/javascript' src='/js/web/admin/ajax_title_edit.js'></script>
	<script type='text/javascript' src='/js/web/admin/admin.js'></script>
	" ) ) {
		echo $script;
	}

	?>

</head>
<body>
<!--==============================header=================================-->

	<div id="header">
			<? include( __DIR__ . "/menu.php" ) ?>
	</div>

<?
 if ( $razdel == '/index.php' ) {
	 ?>
	 <table class="text-head-laitbox">
		 <tbody>
		 <tr>
			 <td class="header" style="text-align: right;">
				 <span style="text-decoration: underline;"><a onclick="goog_report_conversion('tel: 067-76-84-086')" href="#">(067)-76-84-086</a></span><br>
				 <a href="http://annafoto.in.ua/wedding">свадебная фотосъёмка</a>,
				 <a href="http://www.annafoto.in.ua/studio">студийные фотосессии</a>, Одесса, Алексеева Анна
			 </td>
		 </tr>
		 <tr>
			 <td>
				 <div id="slide">
					 <?
					 /** лайтбокс на главной в шапке */
					 if ( $razdel == '/index.php' ) {
						 $slides = glob( 'files/slides/*.jpg' ); // сканирование без субдиректорий
						 $items  = '<div id = "owl-head" class="owl-carousel owl-theme">';
						 $pags   = "<div class='owl-head-pags-hide'><div class='owl-head-pags'>";
						 $i      = 1;
						 foreach ( $slides as $key => $slide ) {
							 $items .= "<div class='item'><img src='/" . $slide . "' alt='свадебный фотограф Алексеева Анна, свадебные фотосессии в Одессе'></div>";
							 $pags .= "<div class='owl-dot' ><strong>0</strong>{$i}</div>";
							 $i ++;
						 }
						 $items .= '</div>';
						 $pags .= '</div></div>';
						 echo( $items );
						 echo( $pags );
					 }
					 ?>
				 </div>
			 </td>
		 </tr>
		 </tbody>
	 </table>
 <?
 }
?>

<!--[if IE 6]>
<div class="warning">
	<noindex>Вы используете устаревший браузер! Для комфортной работы рекомендуем установить новый
		<b>Firefox</b> или <b>Chrome</b> или <b>Internet Explorer</b> или <b>Opera 10</b>!
	</noindex>
</div>
<![endif]-->

<noindex>
	<noscript>
		<div class="warning">
			<strong>В Вашем браузере отключен JavaScript.</strong><br> Для полноценной работы сайта (просмотра галереи фотографий) необходимо,
			<br>чтобы JavaScript был включен. Как включить — смотрите
			<a href="http://www.google.ru/support/adsense/bin/answer.py?answer=12654" target="_blank">здесь</a>.
			<br>После того, как Вы включите JavaScript, перезагрузите страницу (F5).
		</div>
	</noscript>
</noindex>

<?= if_admin( '
	<div class="container-admin">
		<div class="floating-admin">

			<h4>Панель админа:</h4>
			<div>
			    <span>Включить редактирование:</span>
				<button id="enable" class="btn btn-xs" type="button">on/off</button>
				<input type="checkbox" id="edit-content" checked />
			</div>
			<div>
				<span>Включить подсказки:</span>
				<button id="help" class="btn btn-xs" type="button">on/off</button>
			</div>
			<div>
				<a href="/admin.php?adm_out=1" style="padding-right: 20px;">выход</a>
			</div>
	     </div>
	</div>' );
?>
<!-- Голова конец -->