<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:23
 */

require(__DIR__ . '/src/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������
include(ROOT_PATH . 'func.php');
include_once( __DIR__ . '/inc/head.php' );
?>
	<!--==============================content================================-->
	<section>
		<div class="tabs clearfix">
<!--		<div class="page3-row1 pad-2 tabs">-->
			<?
		//	$thumbdir = recursive( '/files',  array('thumb' , 'slides', 'rotate_image_news', 'thu'));
			$thumbdir = recursive_dir('files/portfolio', '.jpg', [ ], [ 'thumb' ], true );
			$i = 1;
			$portfolio = '';
			if ( count( $thumbdir ) ) {
				?>
				<div class="col-8">
					<div class="h-mod">
						<div class="bb-img-red">
					<h3 class="p2">���������:</h3>
							</div></div>
					<ul class="nav list-title">

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
							<div class="h-mod">
						<div class="bb-img-red">
                   							<h3 class="h3-2">' . $name . ':</h3></div></div> <div class="inner">
                       						<ul id="mycarousel-' . $i . '" class="jcarousel-skin-tango">';
							for ( $n = 0; $n < ceil( count( $val ) / 4 ); $n ++ ) {
								$portfolio .= '<li>';
								for ( $m = 0; $m < ( ( count( $val ) - $n * 4 < 4 ) ? count( $val ) - $n * 4 : 4 ); $m ++ ) {
		 	 						$patcUtf8 = WinUtf( $val[$n * 4 + $m], 'w' );
									$img      = preg_replace( '/(\w+\/\w+\/[0-9_a-z�-���-ߨ]*)/i', '$1/thumb', $patcUtf8 );
			 					//	$patcUtf8 = urlencode($patcUtf8);
									$portfolio .= "<a class='plus' href='inc/wm.php?img={$patcUtf8}'>
                                <img class='thumb' src='{$img}' alt='���������� �� ������� {$name} : ". basename($img, '.jpg')."'></a>";

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
				echo '���������� � �������� ���� ���.';
			}
			?>
			</div>
	</section>
	<!--==============================footer================================-->
<?
include_once( __DIR__ . '/inc/footer.php' );
?>