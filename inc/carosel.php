<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 22:15
 */

// require_once( __DIR__ . "/func.php");

/**
 * @param $thumb_link
 *
 * @return string
 */
function photo_link($thumb_link) {

	list($name_dir, $path_thumb) = $thumb_link;
	$name_dir = substr( $name_dir, 3 );
	$patcUtf8 = WinUtf( $path_thumb, 'w' );
	$img_path = str_replace("/thumb", "", $patcUtf8);
	$img_path = str_replace("files/portfolio/", "", $img_path);
	$link  = "<a class='plus' href='/inc/wm.php?img={$img_path}'>";
	$link .= "<img class='thumb' src='/inc/th.php?img={$img_path}' alt='Фотография из раздела \"{$name_dir}\" - \"";
	$link .= basename($patcUtf8, '.jpg')."\"'></a>";

	return $link;
}


/**
 * @return string
 */
function carousel() {

	$thumb = get_random_elements( recursive_dir( "files/portfolio", ".jpg", array( 'thumb' ), array(), false ), 16 ); // сканирование в субдеррикториях 'thumb'

	$carousel = '<div class="page1-row1 pad-1">';
	$carousel .= '<div class="col-1">';
	$carousel .= '<div id="TimerDiv"></div><h3 class="p2"></h3></div>';
	$carousel .= '<div class="col-2">';
	$carousel .= '<h3 class="bb2 p2">Новинки в галереях:</h3>';
	$carousel .= '</div></div>';
	$carousel .= '<div id="owl-index" class="owl-carousel">';
	for ( $i = 0; $i < count( $thumb ); $i = $i + 2 ) {

		$carousel .= "<div>" . photo_link($thumb[$i]);

			if ( isset( $thumb[$i + 1] ) ) {

				$carousel .= photo_link($thumb[$i + 1]);

			}
		$carousel .= "</div>";
	}

	$carousel .= "</div>";
	return $carousel;
}