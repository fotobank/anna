<?php
defined( '_SECUR' ) or die( '������ ��������' );
header( 'Content-type: text/html; charset=windows-1251' );
/**==========================��� ������� "������"====================*/
if ( isset( $_POST['nick'] ) && isset( $_POST['email'] ) ) {
	setcookie( "nick", $_POST['nick'], time() + 300 );
	setcookie( "email", $_POST['email'], time() + 300 );
}
/**==================================================================*/
include( __DIR__ . '/title.php' ); // ����� � �������� ��� ���
list( $razdel, $title ) = title();

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

		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.js'></script>


		<script type="text/javascript" src="/js/minified/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox-thumbs.js"></script>


		<script type='text/javascript' src='/js/tabs.js'></script>
		<script type='text/javascript' src='/js/portfolio.js'></script>
	<?

	} else if ($_SERVER['PHP_SELF'] == '/index.php') {

	?>

		<script type='text/javascript' src='/js/native/jquery-2.1.1.min.js'></script>
		<script type='text/javascript' src='/js/owl.carousel/owl.carousel.js'></script>
		<script type="text/javascript" src="/js/minified/jquery.mousewheel.min.js"></script>
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js"></script>
		<script type='text/javascript' src='/js/minified/index.who.is.online.min.js'></script>
		<script type='text/javascript' src='/js/minified/ajax.tabs.min.js'></script>
		<script type='text/javascript' src='/js/minified/index.slider.min.js'></script>


		<script type='text/javascript' src='/js/native/jquery.mb.browser.min.js'></script>
		<script type='text/javascript' src='/js/minified/tms-0.4.1.min.js'></script>

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
	<script type='text/javascript' src='/js/jeditable/jquery.jeditable.js'></script>

	<link href='/js/jqueryui-editable/css/jqueryui-editable.css' rel='stylesheet'/>
	<script src='/js/native/alloy-ui-master/build/aui/aui-min.js'></script>
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
				<h1>���������������� �������� � ������ ��������� ����</h1>
			</header>
			<? include( __DIR__ . "/menu.php" ) ?>
		</div>

		<? /** �������� �� ������� � ����� */ ?>
		<div id="slide">
			<?
			if ( $razdel == '/index.php' ) {
				$slides = glob( 'files/slides/*.jpg' ); // ������������ ��� �������������
				$items  = '<div class="slider"><ul class="items">';
				$pags   = '<ul class="pags">';
				foreach ( $slides as $key => $slide ) {
					$items .= '<li><img src="/' . $slide . '" alt=""></li>';
					$pags .= '<li><a href="#"><strong>0</strong>' . ++ $key . '</a></li>';
				}
				$pags .= '</ul>';
				$items .= '</ul></div>';
				$items .= $pags;
				echo( $items );

			}

			/*if ( $razdel == '/index.php' ) {
				$slides = glob( 'files/slides/*.jpg' ); // ������������ ��� �������������
				$items  = '<div id = "owl-items" class="owl-carousel owl-theme">';
				foreach ( $slides as $key => $slide ) {
					$items .= '<div class="item"><img src="/' . $slide . '" alt=""></div>';
				}
				$items .= '</div>';
				echo( $items );
			}*/

			?>

		</div>
	</div>
</div>


<!--<script>

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


</script>-->


<!--[if IE 6]>
<div class="warning">
	<noindex>�� ����������� ���������� �������! ��� ���������� ������ ����������� ���������� �����
		<b>Firefox</b> ��� <b>Chrome</b> ��� <b>Internet Explorer</b> ��� <b>Opera 10</b>!
	</noindex>
</div>
<![endif]-->

<noindex>
	<noscript>
		<div class="warning">
			<strong>� ����� �������� �������� JavaScript.</strong><br> ��� ����������� ������ ����� (��������� ������� ����������) ����������,
			<br>����� JavaScript ��� �������. ��� �������� � ��������
			<a href="http://www.google.ru/support/adsense/bin/answer.py?answer=12654" target="_blank">�����</a>.
			<br>����� ����, ��� �� �������� JavaScript, ������������� �������� (F5).
		</div>
	</noscript>
</noindex>

<?= if_admin( '
	<div class="container-admin">
		<div class="floating-admin">

			<h4>������ ������:</h4>
			<div>
			    <span>�������� ��������������:</span>
				<button id="enable" class="btn btn-mini" type="button">enable / disable</button>
			</div>
			<div>
				<span>�������� ���������:</span>
				<button id="help" class="btn btn-mini" type="button">enable / disable</button>
			</div>
			<div>
				<a href="/admin.php?adm_out=1" style="padding-right: 20px;">�����</a>
			</div>
	     </div>
	</div>' );
?>
<!-- ������ ����� -->