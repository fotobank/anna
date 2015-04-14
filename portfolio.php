<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:23
 */

include( __DIR__ . '/inc/config.php' ); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );
?>
	<!--==============================content================================-->
	<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<!--		<div class="page3-row1 pad-2 tabs">-->
			<?
		//	$thumbdir = recursive( '/files',  array('thumb' , 'slides', 'rotate_image_news', 'thu'));
			$thumbdir = recursive_dir("files/portfolio", ".jpg", [ ], [ 'thumb' ], true );
			$i = 1;
			$portfolio = '';
			if ( count( $thumbdir ) ) {
				?>
				<div class="col-8">
					<h3 class="bb1 p2">Категории:</h3>
					<ul class="nav list-categorii">

						<?
						foreach ( $thumbdir as $name => $val ) {
							$name = substr( $name, 3 );
		 	 				$name = WinUtf( $name, 'w' );
							if ( $i == 1 ) {
								?>
								<li id = "<?='head-'.$i?>" class="text selected"><a href="<?= '#tab-' . $i ?>"><?= $name ?></a></li>
							<? } else { ?>
								<li id = "<?='head-'.$i?>" class="text"><a href="<?= '#tab-' . $i ?>"><?= $name ?></a></li>
							<?
							}
							$portfolio .= '<div id="tab-' . $i . '" class="tab-content gallery-photo">
                   							<h3 class="h3-2">' . $name . ':</h3> <div class="inner">
                       						<ul id="mycarousel-' . $i . '" class="jcarousel-skin-tango">';
							for ( $n = 0; $n < ceil( count( $val ) / 4 ); $n ++ ) {
								$portfolio .= '<li>';
								for ( $m = 0; $m < ( ( count( $val ) - $n * 4 < 4 ) ? count( $val ) - $n * 4 : 4 ); $m ++ ) {
		 	 						$patcUtf8 = WinUtf( $val[$n * 4 + $m], 'w' );
									$img      = preg_replace( '/(\w+\/\w+\/[0-9_a-zа-яёА-ЯЁ]*)/i', '$1/thumb', $patcUtf8 );
			 						$patcUtf8 = urlencode($patcUtf8);
									$portfolio .= "<a class='plus' href='inc/wm.php?img={$patcUtf8}'>
                                <img class='thumb' src='{$img}' alt='Фотография из раздела {$name} №_".basename($img, '.jpg')."'></a>";

								}
								$portfolio .= '</li>';
							}
							$portfolio .= '</ul></div></div>';
							$i ++;
						}
						?>
					</ul>
			</div>
				<div class="col-9">
					<?= $portfolio ?>
				</div>
			<?
			} else {
				echo "Фоторгафий в альбомах пока нет.";
			}
			?>
	</div>

	</aside>
	<!--==============================footer================================-->
<?
include_once( __DIR__ . '/inc/footer.php' );
?>