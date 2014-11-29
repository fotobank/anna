<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:24
 */

include( __DIR__ . '/inc/config.php' ); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );

$tune = array(
		'time'   => 7, // интервал обновления в днях
		'upd_db' => false // true - обновить принудительно (для настройки)
);
$parser = new citates( $tune );

?>
	<!--==============================content================================-->
	<section id="content">
		<!--        <div id="razrab"></div>-->
		<div class="pad-1">
			<div class="page5-row1">
				<div class="col-12">
					<h3 class="bb3 p2">Все новости:</h3>

					<div id="zapolnen"></div>
					<!--                    <ul class="list-3">-->
					<!--                        <li><span class="date"><span>09</span>08</span><span class="extra-wrap"><a href="#">Gravida ut viverra lectus tincidunt cras.</a><br>-->
					<!--            nec tristique. Sed sed felis arcu, vel vehicula augue sagittis cursus. Fusce tincidunt, tellus eget tristique cursus, orci mi iaculis sem.</span></li>-->
					<!--                        <li><span class="date"><span>05</span>08</span><span class="extra-wrap"><a href="#">Sed sed felis arcu, vel vehicula augue sagittis cursus.</a><br>-->
					<!--            Fusce tincidunt, tellus eget tristique cursus, orci mi iaculis sem., sit amet dictum velit velit eu magna. Nunc viverra nisi sed orci tincidunt at hendrerit orci luctus. </span></li>-->
					<!--                        <li class="bot-0"><span class="date"><span>02</span>08</span><span class="extra-wrap"><a href="#">Fusce tincidunt, tellus eget tristique cursus, orci mi iaculis sem., sit amet dictum velit.</a><br>-->
					<!--            Nunc viverra nisi sed orci tincidunt at hendrerit orci luctus. Suspendisse tincidunt, ipsum at semper facilisis, turpis nisi dictum diam, nec tincidunt neque velit in nunc. Fusce sed leo sapien. Vestibulum non nunc ac tellus sollicitudin iaculis. </span></li>-->
					<!--                    </ul>-->
					<!--                    <a href="#" class="link-1 link-1-pad">Read more</a> -->

				</div>


				<div class="col-13">
					<h3 class="bb3 p2">Разное:</h3>
					<img src = "inc/rotate.php" alt="" class="img-border img-indent-2">
					<p class="upper clr-1 p5 ">Цитаты известых людей: </p>

					<div class="slovo">
						<p class="clr-1"><?= $parser->citata; ?></p>
					</div>
					<div class="link-2 link-1-pad"><?= $parser->avtor; ?></div>
				</div>
			</div>

		</div>
	</section>
	<!--==============================footer================================-->
<?
include_once( __DIR__ . '/inc/footer.php' );
?>