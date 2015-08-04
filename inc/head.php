<?php
/*
  Todo    - шапка проекта
  @author - Jurii
  @date   - 03.04.2015
  @time   - 8:43
*/

defined( 'PROTECT_PAGE' ) or die( 'Доступ запрещен' );
// header( 'Content-type: text/html; charset=windows-1251' );
/**==========================для раздела "отзывы"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/
include( SITE_PATH . 'inc/title.php' ); // титры в разделах для СЕО
list( $current_razdel, $title, $keywords, $description ) = title();

?>
<!DOCTYPE html>
<html lang="ru" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title><?= $title ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta name="author" lang="ru" content="Алексеева Анна | фотограф в Одессе">
	<meta name="keywords" content="<?= $keywords ?>">
	<meta name="description" content="<?= $description ?>">
	<link rel="icon" href="/images/favicon.png"  type="image/png" />
	<link rel="shortcut icon" href="/images/favicon.png" />


	<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/ie.css">
	<script src="/js/if_IE/html5.js"></script>
	<![endif]-->


	<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>
	<script type='text/javascript' src='/js/jquery.easing.1.3.js'></script>
	<script type='text/javascript' src='/js/native/jquery.mb.browser.min.js'></script>

	<script type='text/javascript' src='/js/functions.js'></script>
	<script type='text/javascript' src='/js/web/who.is.online.js'></script>


	<?
	if ( $_SERVER['PHP_SELF'] == '/portfolio.php' ) {
		?>


	    <link rel='stylesheet' type='text/css' media='screen' href='/css/skin-2.css'>


		<script type='text/javascript' src='/js/jquery.jcarousel.min.js'></script>

		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.min.js'></script>


		<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox-thumbs.js"></script>

		<script type='text/javascript' src='/js/web/jquery.jurii.ajax.hash.control.js'></script>
		<script type='text/javascript' src='/js/portfolio.js'></script>


	<?

	} else if ($_SERVER['PHP_SELF'] == '/services.php') {
    ?>
		<script type='text/javascript' src='/js/jquery.jurii.ajax-load.js'></script>
		<script type='text/javascript' src='/js/web/jquery.jurii.ajax.hash.control.js'></script>
     <?

	} else if ($_SERVER['PHP_SELF'] == '/index.php') {

	?>

		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.min.js'></script>
		<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>

		<script type='text/javascript' src='/js/jquery.jurii.ajax-load.js'></script>
		<script type='text/javascript' src='/js/web/jquery.jurii.ajax.hash.control.js'></script>
		<script type="text/javascript" src="/js/jquery-contained-sticky-scroll.js"></script>
		<script type='text/javascript' src='/js/index.slider.js'></script>
		<script type='text/javascript' src='/js/clock.js'></script>

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
<?= if_admin( '
	<div class="container-admin">
		<div class="floating-admin">

			<h4>Панель админа:</h4>
			<div>
			    <span>Включить редактирование:</span>
				<button id="enable" class="btn-xs" type="button"><div class="btn-off"></div></button>
			</div>
			<div>
				<span>Включить подсказки:</span>
				<button id="help" class="btn-xs" type="button"><div class="btn-off"></div></button>
			</div>
			<div>
				<a href="/admin.php?adm_out=1" style="padding-right: 20px;">выход</a>
			</div>
	     </div>
	</div>' );
?>
<!--==============================header=================================-->
<article>
	<div id="header">
			<? include( __DIR__ . "/menu.php" ) ?>
	</div>
</article>
<div id="wrapper-content">
	<div id="top-inset"></div>
<!--[if IE 9]>
<div class="alert alert-danger">
	<noindex>Вы используете устаревший браузер! Для комфортной работы рекомендуем установить новый
		<b>Firefox</b> или <b>Chrome</b> или <b>Internet Explorer</b> или <b>Opera 10</b>!
	</noindex>
</div>
<![endif]-->

<noindex>
	<noscript>

		<div class="alert alert-danger">
			<strong>В Вашем браузере отключен JavaScript.</strong><br> Для полноценной работы сайта (просмотра галереи фотографий) необходимо,
			<br>чтобы JavaScript был включен. Как включить — смотрите
			<a href="http://www.google.ru/support/adsense/bin/answer.py?answer=12654" target="_blank">здесь</a>.
			<br>После того, как Вы включите JavaScript, перезагрузите страницу (F5).
		</div>
	</noscript>
</noindex>

<!-- Голова конец -->

<div id="main" class="clearfix">