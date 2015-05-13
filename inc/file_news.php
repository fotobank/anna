<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 22:36
 *
 * @param $fileNewsPatch
 *
 * @return string
 */

function getFileNews($fileNewsPatch) {
	$print = "���� �������� �� ������";
	$news = ( file_exists( $fileNewsPatch ) ) ? file_get_contents( $fileNewsPatch ) : $print;
	if ( $news != $print) {
		$news = explode( "||", replaceBBCode( $news ) );
		if ( count( ( $news ) ) ) {
			for ( $i = 0; $i < count( $news ); $i ++ ) {
				$new = explode( "[]", $news[$i] );
				if ( count( $new ) > 0 ) {

					$print .= "<div class='adr'>";
					$print .= "<p class='title'>{$new[0]}</p>";
					$print .= isset( $new[1] ) ? "<div>".$new[1]."</div>" : '';
					$print .= isset( $new[2] ) ? "<a href='/news.php' class='link-1 link-2-pad'>��������</a>" : '';
					$print .= "</div>";

				}
			}
		}
	}
	return $print;
}