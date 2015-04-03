<?php
defined('_SECUR') or die('Доступ запрещен');
require_once (__DIR__. "/../inc/sitemap_generator.php");
require( __DIR__ . '/social_icons.php' );
require( __DIR__ . '/online_widget.php' );

//запуск главного меню
?>
	<script type='text/javascript' src='/js/menu.js'></script>
<?

if(DEBUG_MODE == false) {
echo
	"<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-49928883-2', 'auto');
		ga('send', 'pageview');
		ga('set', '&uid', {{$_COOKIE['PHPSESSID']}}); // Задание идентификатора пользователя с помощью параметра user_id (текущий пользователь).
	</script>";

}

?>
<footer>
<div class='social'>
<!--==============================соцсети================================-->
				<div class="h-mod fleft">
				<h3 class="bb3">Поделиться с друзьями:</h3>
				</div>
					<?
if(DEBUG_MODE == true) {
	$bench->end();
	echo "<span>Время: ".$bench->getTime()." / ";
	echo "Память пиковая: ".$bench->getMemoryPeak()." / ";
	echo "Память конечная: ".$bench->getMemoryUsage()."</span>";
}
?>
<div class="clear"></div>
					<?= social_icons() . online_widget() ?>
</div>

<div class='container'>

	<span class="copirait"><strong>© <?=auto_copyright('2011')?> Алексеева Анна </strong></span><br>
	<span> cтудия&nbsp;&nbsp; <a target='_blank' href='http://www.aleks.od.ua' class='link-2'>Creative ls</a></span>

</div>

</footer>


</body>
</html>
<?
ob_end_flush();