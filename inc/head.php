<?php
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


/** --Initialize the class with image encoding, gzip, a timer, and use the google closure API---*/
$vars      = array(
	'encode'           => true,
	'timer'            => true,
	'gzip'             => true,
	'packer'           => 'minifier', // closure / jsminplus / packer  / minifier / jsmin
	'remove_comments'  => true,
	'hashed_filenames' => false // Generate hashbased filenames to break caches
);
$minimized = new CssJsCompress\Compressor( $vars );
/** --------------------------------------------------------------------------------------------*/

?>
<!DOCTYPE html>
<html lang="ru" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title><?= $title ?></title>
	<meta charset="windows-1251">
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

	<link rel="stylesheet" href="/js/native/alloy-ui-master/build/aui-css/css/bootstrap.css">

	<?
	$exclude_styles = array(
		'css/skin-2.css',
		'css/skin.css',
		'css/ie.css'
	);
	$prioritize     = array(
		'css/reset.css',
		'css/style.css',
		'css/jquery.fancybox-1.3.4.css',
		'css/tabs.css',
		'css/slider.css'
	);

	?>
	<!--	<link rel="stylesheet" href="--><?php //$minimized->merge( 'cache/css/master.min.css', 'css', 'css', $exclude_styles, $prioritize ); ?><!--" />-->

	<!--	<link rel="stylesheet" href="/css/owl.carousel.css">-->
	<!--	<link rel="stylesheet" href="/css/owl.theme.css">-->
	<!--	<link rel="stylesheet" href="/css/owl.transitions.css">-->

	<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">

	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="screen" href="/css/ie.css">
	<script src="/js/if_IE/html5.js"></script>
	<![endif]-->
	<?

	if ($_SERVER['PHP_SELF'] == '/portfolio.php') {

	//
	?><!--<link rel="stylesheet" type="text/css" media="screen" href="/css/skin-2.css">--><?//

$exclude    = array(

	'js/jquery-ui.min.js',
	'js/index.who.is.online.js',
	//	'js/tms-0.4.1.js',
	'js/index.slider.js',
	'js/ajax.tabs.js'

);
$prioritize = array(
	'js/jquery-1.7.js',
	'js/jquery.easing.1.3.js',
	'js/jquery.jcarousel.min.js',
	'js/jquery.fancybox-1.3.4.js',
//			'js/ajax.tabs.js',
	'js/portfolio.js',
	'js/functions.js'
);
$cash       = 'cache/js/portfolio.min.js';

} else if ( $_SERVER['PHP_SELF'] == '/index.php' ) {

	?>
	<!--		<link rel="stylesheet" type="text/css" media="screen" href="/css/skin.css">-->

	<script type='text/javascript' src='/js/native/jquery-1.11.1.min.js'></script>
	<!--<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>-->
	<script type='text/javascript' src='/js/native/jquery.mb.browser.min.js'></script>
	<!--<script type='text/javascript' src='/js/native/jquery-migrate-1.2.1.js'></script>-->

	<script type='text/javascript' src='/js/native/jquery-ui-1.11.1/jquery-ui.js'></script>

	<script src="/js/native/alloy-ui-master/build/aui/aui-min.js"></script>

	<?


	$exclude    = array(
		'js/jquery-1.7.js',
		'js/ajax.tabs.js',
		'js/jquery-ui.min.js',

		'js/jquery.fancybox-1.3.4.js',
		'js/portfolio.js'
	);
	$prioritize = array(
//			'js/jquery-1.7.js',


		'js/jquery.easing.1.3.js',
		//	'js/tms-0.4.1.js',
		'js/owl.carousel.js',
		'js/jquery.jcarousel.min.js',
		'js/index.slider.js',
//			'js/ajax.tabs.js',
		'js/functions.js',
		'js/index.who.is.online.js'
	);
	$cash       = 'cache/js/index.min.js';
} else {
	$exclude    = array(
		'js/jquery.fancybox-1.3.4.js',
		'js/jquery-ui.min.js',
		'js/index.who.is.online.js',
		'js/portfolio.js',
		'js/index.slider.js',
		'js/ajax.tabs.js',
		'js/jquery.jcarousel.min.js',
		//	'js/tms-0.4.1.js'
	);
	$prioritize = array(
		'js/jquery-1.7.js',
		'js/jquery.easing.1.3.js',
		'js/functions.js'
	);
	$cash       = 'cache/js/master.min.js';
}
?>




	<script src="<?php $minimized->merge( $cash, 'js', 'js', $exclude, $prioritize ); ?>"></script>

	<script src="/js/ajax.tabs.js"></script>
	<script src="/js/web/index/index.async.load.js"></script>



	<?
	if ( if_admin( $script = "
	<!--<script type='text/javascript' src='/js/jeditable/jquery.jeditable.js'></script>-->
	<link href='/js/jqueryui-editable/css/jqueryui-editable.css' rel='stylesheet'/>
	<script src='/js/jqueryui-editable/js/jqueryui-editable.js'></script>
	<script type='text/javascript' src='/js/web/admin/ajax_title_edit.js'></script>
	<script type='text/javascript' src='/js/web/admin/admin.js'></script>
	" ) ) {
		echo $script;
	};
	?>

</head>
<body>
<!--==============================header=================================-->
<div class="header">
	<div id="header">

		<div class=<?= ( $razdel == '/index.php' ) ? "nav" : "subpages-nav" ?>>

			<header class="gallery-block">
				<h1>Профессиональный фотограф в Одессе Алексеева Анна</h1>
			</header>

			<nav class="centered-navigation">
				<div class="centered-navigation-wrapper">
					<a href="/index.php" class="mobile-logo">
						<img src="/images/logo.png" alt="">
					</a>
					<a href="" class="centered-navigation-menu-button">MENU</a>
					<?
					if ( $razdel ) {
						?>
						<ul class="centered-navigation-menu"
							>
							<li <?= ( $razdel == '/index.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
								<a href="/index.php">Главная</a></li
								>
							<li <?= ( $razdel == '/about.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
								<a href="/about.php">Об&nbsp;&nbsp;авторе</a></li
								>
							<li <?= ( $razdel == '/portfolio.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
								<a href="/portfolio.php">Портфолио</a></li
								>
							<li class="nav-link logo">
								<a href="/index.php" class="logo">
									<img src="/images/logo.png" alt="Логотип сайта">
								</a>
							</li
								>
							<li <?= ( $razdel == '/news.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
								<a href="/news.php">Новости</a></li
								>
							<li <?= ( $razdel == '/services.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
								<a href="/services.php">Услуги</a></li
								>
							<li <?= ( $razdel == '/comments.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
								<a href="/comments.php">Гостевая</a></li
								>
						</ul>
					<?
					}
					?>
				</div>
			</nav>

			<script>
				$(document).ready(function () {
					var menu = $('.centered-navigation-menu');
					var menuToggle = $('.centered-navigation-menu-button');
					var signUp = $('.sign-up');

					$(menuToggle).on('click', function (e) {
						e.preventDefault();
						menu.slideToggle(function () {
							if (menu.is(':hidden')) {
								menu.removeAttr('style');
							}
						});
					});
				});
			</script>

		</div>
		<div id="slide">
			<?
			/*if ( $razdel == '/index.php' ) {
				$slides = glob( 'files/slides/*.jpg' ); // сканирование без субдиректорий
				$items  = '<div class="slider"><ul class="items">';
				$pags = '<ul class="pags">';
				foreach ( $slides as $key => $slide ) {
					$items .= '<li><img src="/' . $slide . '" alt=""></li>';
					$pags .= '<li><a href="#"><strong>0</strong>' . ++$key . '</a></li>';
				}
				$pags .= '</ul>';
				$items .= '</ul></div>';
				$items .= $pags;
				echo( $items );

			}*/

			if ( $razdel == '/index.php' ) {
				$slides = glob( 'files/slides/*.jpg' ); // сканирование без субдиректорий
				$items  = '<div id = "owl-items" class="owl-carousel owl-theme">';
				foreach ( $slides as $key => $slide ) {
					$items .= '<div class="item"><img src="/' . $slide . '" alt=""></div>';
				}
				$items .= '</div>';
				echo( $items );
			}

			?>

		</div>
	</div>
</div>


<script>

	$(document).ready(function () {

		$("#owl-items").owlCarousel({
			autoPlay         : 15000,
			stopOnHover      : true,
			navigation       : false,
			paginationSpeed  : 1000,
			goToFirstSpeed   : 5000,
			singleItem       : true,
			autoHeight       : true,
			paginationNumbers: true,

			transitionStyle: "fade"  //Currently available: "fade", "backSlide", "goDown", "fadeUp"
		});


	});


</script>


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
				<button id="enable" class="btn btn-mini" type="button">enable / disable</button>
			</div>
			<div>
				<span>Включить подсказки:</span>
				<button id="help" class="btn btn-mini" type="button">enable / disable</button>
			</div>
			<div>
				<a href="/admin.php?adm_out=1" style="padding-right: 20px;">выход</a>
			</div>
	     </div>
	</div>' );
?>
<!-- Голова конец -->