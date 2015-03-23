<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 05.08.14
 * Time: 22:15
 */

// require_once( __DIR__ . "/func.php");

/**
 * @return string
 */
function carousel() {

	//  $thumb = my_array_rand(recursive('/files', true), 16);  // вторая рандом функция
	$thumb = get_random_elements( recursive_dir( "files/portfolio", ".jpg", array( 'thumb' ), array(), false ), 16 ); // сканирование в субдеррикториях 'thumb'

	$carousel = '<div class="page1-row1 pad-1">';
	$carousel .= '<div class="col-1">';
	$carousel .= '<div id="TimerDiv"></div><h3 class="p2"></h3></div>';
	$carousel .= '<div class="col-2">';
	$carousel .= '<h3 class="bb2 p2">Новинки в галереях:</h3>';
	$carousel .= '</div></div>';
	$carousel .= "<ul id='mycarousel' class='jcarousel-skin-tango gallery-photo'>";
	for ( $i = 0; $i < count( $thumb ); $i = $i + 2 ) {

		$carousel .= "<li><a href='/portfolio.php'><img class='new-slider' src='" . WinUtf( $thumb[$i], 'w' ) . "' ";
		$carousel .= "alt='" . basename( WinUtf( $thumb[$i], 'w' ) ) . " '></a>";

		if ( isset( $thumb[$i + 1] ) ) {

			$carousel .= "<a href='/portfolio.php'>";
			$carousel .= "<img class='new-slider' src='" . WinUtf( $thumb[$i + 1], 'w' ) . "' ";
			$carousel .= "alt='" . basename( WinUtf( $thumb[$i + 1], 'w' ) ) . " '></a>";
		}
		$carousel .= "</li>";
	}
	$carousel .= "</ul>";
	return $carousel;
}
	?>

	<!--<div id='new-gal'>
		<div class="page1-row1 pad-1">
			<div class="col-1">
				<div id="TimerDiv"></div>
				<h3 class="p2"></h3></div>
			<div class="col-2"><h3 class="bb2 p2">Новинки в галереях:</h3></div>
		</div>
		<div class="jcarousel-wrapper">
			<div class="mycarousel">
		<ul>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/005.jpg' alt='005.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/001.jpg' alt='001.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/006.jpg' alt='006.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/06_Портреты/thumb/001.jpg' alt='001.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/009.jpg' alt='009.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/008.jpg' alt='008.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/dodelat.jpg' alt='dodelat.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/004.jpg' alt='004.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/06_Портреты/thumb/002.jpg' alt='002.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/06_Портреты/thumb/003.jpg' alt='003.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/002.jpg' alt='002.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/06_Портреты/thumb/004.jpg' alt='004.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/003.jpg' alt='003.jpg '></a><a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/007.jpg' alt='007.jpg '></a>
			</li>
			<li>
				<a href='/portfolio.php'><img class='new-slider' src='/files/portfolio/01_Свадьбы/thumb/руссязык.jpg' alt='руссязык.jpg '></a>
			</li>
		</ul>
			</div>


			<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
			<a href="#" class="jcarousel-control-next">&rsaquo;</a>
		</div>
	</div>-->

	<!--<div class="jcarousel-wrapper">
		<div class="jcarousel">
			<ul>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/005.jpg');"></li>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/003.jpg');"></li>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/003.jpg');"></li>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/003.jpg');"></li>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/003.jpg');"></li>
				<li style="background: url('/files/portfolio/01_Свадьбы/thumb/003.jpg');"></li>
			</ul>
		</div>

		<p class="photo-credits">
			Photos by <a href="http://www.mw-fotografie.de">Marc Wiegelmann</a>
		</p>

		<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
		<a href="#" class="jcarousel-control-next">&rsaquo;</a>
	</div>-->
<?