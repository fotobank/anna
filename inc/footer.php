<?php
defined( '_SECUR' ) or die( 'Доступ запрещен' );
require_once( __DIR__ . "/../inc/sitemap_generator.php" );
require( __DIR__ . '/social_icons.php' );
require( __DIR__ . '/online_widget.php' );

//запуск главного меню
?>
	<script type='text/javascript' src='/js/menu.js'></script>


<!-- Google Code for annafoto2 Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996577394;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "msCcCJq9-VoQ8qCa2wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt=""
src="//www.googleadservices.com/pagead/conversion/996577394/?label=msCcCJq9-VoQ8qCa2wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<!-- Google Code for annafoto_mobil Conversion Page
In your html page, add the snippet and call
goog_report_conversion when someone clicks on the
phone number link or button. -->
<script type="text/javascript">
  /* <![CDATA[ */
  goog_snippet_vars = function() {
    var w = window;
    w.google_conversion_id = 996577394;
    w.google_conversion_label = "yYYqCNLtgVsQ8qCa2wM";
    w.google_remarketing_only = false;
  }
  // DO NOT CHANGE THE CODE BELOW.
  goog_report_conversion = function(url) {
    goog_snippet_vars();
    window.google_conversion_format = "3";
    window.google_is_call = true;
    var opt = new Object();
    opt.onload_callback = function() {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  }
  var conv_handler = window['google_trackConversion'];
  if (typeof(conv_handler) == 'function') {
    conv_handler(opt);
  }
}
/* ]]> */
</script>
<script type="text/javascript"
  src="//www.googleadservices.com/pagead/conversion_async.js">
</script>


<?

if ( DEBUG_MODE == false ) {
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
<!--==============================соцсети================================-->
<div class='social'>
				<div class="h-mod w100">
				<h3 class="bb3 fleft w100">Поделиться с друзьями:</h3>
					<?
					if ( DEBUG_MODE == true ) {
						$bench->end();
						echo "<span class='texno'>Время: " . $bench->getTime() . " / ";
						echo "Память пиковая: " . $bench->getMemoryPeak() . " / ";
						echo "Память конечная: " . $bench->getMemoryUsage() . "</span>";
					}
					?>
				</div>
		<?= social_icons() . online_widget() ?>
</div>

<!-- ==========================копирайт==================================-->
<div class='container'>

	<span class="copirait"><strong>© <?= auto_copyright( '2011' ) ?> Алексеева Анна </strong></span><br>
	<span> cтудия&nbsp;&nbsp; <a target='_blank' href='http://www.aleks.od.ua' class='link-2'>Creative ls</a></span>

</div>
</footer>

</body>
</html>
<?
ob_end_flush();