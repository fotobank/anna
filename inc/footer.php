</div>
<div id="bottom-inset"></div>
</div>
<?php
defined( '_SECUR' ) or die( 'Доступ запрещен' );
require_once( __DIR__ . "/../inc/sitemap_generator.php" );
require( __DIR__ . '/online_widget.php' );


?>
<footer>
<!--==============================соцсети================================-->
<div class='social'>
				<div class="h-mod">
						<div class="bb-img-red">
				<h3>Поделиться с друзьями:</h3>
							</div>
					<?
if ( DEBUG_MODE == true ) {
	$bench = Ubench_Test::getInstance()->end();
	echo "<span class='texno'>Время: " . $bench->getTime() . " / ";
	echo "Память пиковая: " . $bench->getMemoryPeak() . " / ";
	echo "Память конечная: " . $bench->getMemoryUsage() . "</span>";
}
?>
				</div>
	<div class="share42init"></div>
		<?= online_widget() ?>
</div>

<!-- ==========================копирайт==================================-->
<div class='container'>
	<?
	if ( DEBUG_MODE == false ) { include (__DIR__ . "/inc.foter.google.php"); }
	?>
	<span class="copirait"><strong>© <?= auto_copyright( '2011' ) ?> Алексеева Анна </strong></span><br>
	<span> cтудия&nbsp;&nbsp; <a target='_blank' href='http://www.aleks.od.ua' class='link-2'>Creative ls</a></span>

</div>
</footer>
<script type='text/javascript' src='/js/menu.js'></script>
<script type='text/javascript' src='/js/share42/share42.js'></script>
</body>
</html>
<?
ob_end_flush();