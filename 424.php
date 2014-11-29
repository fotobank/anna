<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 16.07.14
 * Time: 0:26
 */
include( __DIR__ . '/inc/config.php' ); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );

?>
<!--==============================content================================-->
<body>
<section id="content">
	<div style="padding: 100px;">
<table height="100%" width="100%">
	<tbody>
	<tr>
		<td align="center" height="100%" valign="middle" width="100%">
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
							<tr>
								<td>
									<img src="images/error/top_left.gif" alt="" border="0" height="38" width="1">
								</td>
								<td><img src="images/error/stop.gif" alt="" border="0" height="38">
								</td>
								<td background="images/error/top_bg.gif" width="100%">
									<img src="images/error/1x1.gif" alt="" border="0" height="1" width="1">
								</td>
								<td>
									<img src="images/error/top_right.gif" alt="" border="0" height="38" width="123">
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" height="21" width="100%">
							<tbody>
							<tr>
								<td background="images/error/tom_med_left.gif" width="1">
									<img src="images/error/1x1.gif"></td>
								<td bgcolor="#e7ebf6" width="354">
									<center><font style="font-weight: bold; font-size: 14px;
								 color: rgb(204, 33, 40); font-family: Tahoma,serif;">
											<p>Ошибка 424:<br>Ваше действие заблокировано<br> правилами безопасности
											сайта!
										</font></center>
								</td>
								<td background="images/error/tom_med_left.gif" width="1">
									<img src="images/error/1x1.gif"></td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
							<tr>

								<td bgcolor="#e7ebf6" width="100%">
									<img src="images/error/1x1.gif" alt="" border="0" height="1" width="1">
								</td>
								<td background="images/error/tom_med_left.gif" width="1">
									<img src="images/error/1x1.gif"></td>
							</tr>

							<tr>

								<td bgcolor="#e7ebf6" width="100%">
									<center>
										<font style="font-size: 11px; color: rgb(36, 51, 138); font-family: Tahoma;"></font>
									</center>
								</td>
								<td background="images/error/tom_med_left.gif" width="1">
									<img src="images/error/1x1.gif"></td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>

				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
	</div>
</section>
</body>
<?
include_once( __DIR__ . '/inc/footer.php' );
?>