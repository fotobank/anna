<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 16.07.14
 * Time: 0:26
 */
require (__DIR__ .'/system/config/config.php'); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );
?>
	<link rel="stylesheet" href="/css/404.css" media="screen" type="text/css" />
<!--==============================content================================-->

		<div class="error-424">
			<div class="inner-424">
				<div class="wrap-424">
    <pre>
		<code>
			<span class="green">&lt;!</span><span>DOCTYPE html</span><span class="green">&gt;</span>
			<span class="orange">&lt;html&gt;</span>
			   <span class="orange">&lt;style&gt;</span>
			     * {
			        <span class="green">breaking</span>:<span class="blue"> bad</span>;
			      }
			   <span class="orange">&lt;/style&gt;</span>
			<span class="orange">&lt;body&gt;</span>
			<span class="white">
			Ошибка 424:
			Ваше действие заблокировано правилами безопасности сайта!
			</span>
<span class="info-424">
<br/><br/>
	                 <span class="orange">&nbsp;&lt;/body&gt;</span>
	             <span class="orange">&lt;/html&gt;</span>
</span>
		</code>
	</pre>
				</div>
			</div>
		</div>



<?
include_once( __DIR__ . '/inc/footer.php' );
?>