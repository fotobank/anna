<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:24
 */
defined('_SECUR') or die('Доступ запрещен');
?>
	<script type="text/javascript">
		// Добавить в Избранное
		function add_favorite(a) {
			title=document.title;
			url=document.location;
			try {
// Internet Explorer
				window.external.AddFavorite(url, title);
			}
			catch (e) {
				try {
// Mozilla
					window.sidebar.addPanel(title, url, "");
				}
				catch (e) {
// Opera
					if (typeof(opera)=="object") {
						a.rel="sidebar";
						a.title=title;
						a.url=url;
						return true;
					}
					else {
// Unknown
						alert('Ваш браузер не поддерживает автоматическое добавление закладок. Нажмите Ctrl-D чтобы добавить страницу в закладки.');
					}
				}
			}
			return false;
		}
	</script>

			<div class="page6-row1 pad-1">
				<div class="col-14">
					<h3 class="bb1">Для связи:</h3>
					<dl>
						<dd>Удобное для меня место для встреч:</dd>
						<dd class="clr-1"><p>г.Одесса ул.Троицкая <br>угол ул. Канатной, кафе "Cхiд".</p></dd>
						<dd>По всем вопросам обращайтесь с 9.00 до 21.00 по телефонам:</dd>
						<dd class="clr-1"><p><a onclick="goog_report_conversion('tel: 094-95-53-167');return false;" href="#">Рабочий: (094)95-53-167</a></p></dd>
						<dd class="clr-1"><p><a onclick="goog_report_conversion('tel: 067-76-84-086');return false;" href="#">Мобильный: (067)76-84-086</a></p></dd>
						<dd>Или пишите:</dd>
						<dd class="p3 clr-1"><span>Email:</span>
							<a href="#" class="link">
								<script type="text/javascript">
									var xv1='ailt';
									var xv2='hre';
									var xv3='f="m';
									var xv4='o:';
									var xv5='bestfoto';
									var xv6='i.ua';
									document.write('<a '+xv2+xv3+xv1+xv4+xv5+'@'+xv6+'">'+'bestfoto'+'@'+xv6+'</a>');
								</script>
							</a>
						</dd>
					</dl>
					<a href="#"  onclick="return add_favorite(this);">Добавить в Избранное</a>
				</div>
				<div class="col-15">
					<h3 class="bb1 p2">Карта:</h3>

					<div>
						<img src="/images/map.png" alt="" class="img-border img-indent-2">
					</div>
				</div>
				<div class="col-16">
					<h3 class="bb1 p2">Жду Ваших писем:</h3>

					<form id="form" method="post" action="/about.php">
						<fieldset>
							<label><input  type='text' name='nick' value='' required
												   pattern="[?a-zA-Zа-яА-Я0-9_-]{2,20}" placeholder="Представьтесь"
												   title="Допустимы любые латинские и русские буквы, цифры, подчеркивание и тире в колличестве от 2 до 20шт.">
							</label>
							<label style="position: absolute; margin-left: -10000px;">
								<input type='text' name='name' value='cool'>
							</label>
							<label>
								<input type='email' name='email' value='' required
													  pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
													  placeholder="E-mail"
													  title="Допустимы любые латинские буквы, цифры и подчеркивание.">
							</label>
							<label>
								<textarea name='mess' cols='90' rows='6' placeholder="Сообщение" required maxlength="300"></textarea>
							</label>
							<label style="width: 100px; float: right; margin-top: 6px; margin-right: 2px;">
								<input type='submit' name='submit' value='отправить'>
							</label>
						</fieldset>
					</form>
				</div>
			</div>