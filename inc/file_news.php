<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 22:36
 */

function get_filenews($file_news) {
	$print = '';
	$news = ( file_exists( $file_news ) ) ? file_get_contents( $file_news ) : '';
	if ( $news ) {
		$news = explode( "||", replaceBBCode( $news ) );
		if ( count( ( $news ) ) ) {
			for ( $i = 0; $i < count( $news ); $i ++ ) {
				$new = explode( "[]", $news[$i] );
				if ( count( $new ) > 0 ) {

					$print .= "<div class='adr'>";
					$print .= "<p class='title'>{$new[0]}</p>";
					$print .= isset( $new[1] ) ? "<div class='h2'>".$new[1]."</div>" : '';
					$print .= isset( $new[2] ) ? "<a href='/news.php' class='link-1 link-2-pad'>подробно</a>" : '';
					$print .= "</div>";

				}
			}
		}
	}
	return $print;
}