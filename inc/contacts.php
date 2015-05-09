<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:24
 */
defined( '_SECUR' ) or die( 'Доступ запрещен' );
?>
<script type="text/javascript">
	// Добавить в Избранное
	function add_favorite(a) {
		title = document.title;
		url = document.location;
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
				if (typeof(opera) == "object") {
					a.rel = "sidebar";
					a.title = title;
					a.url = url;
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
		<div class="h-mod">
			<div class="bb-img-red">
				<h3>Для связи:</h3>
			</div>
		</div>
		<dl>
			<dd>Удобное для меня место для встреч:</dd>
			<dd class="clr-1"><p>г.Одесса ул.Троицкая <br>угол ул. Канатной, кафе "Cхiд".</p></dd>
			<dd>По всем вопросам обращайтесь с 9.00 до 21.00 по телефонам:</dd>
			<dd class="clr-1">
				<p>
					<span>Рабочий:</span>
					<a class="link" onclick="goog_report_conversion('tel: 094-95-53-167');return false;" href="#">
						<span class="phone">(094)95-53-167</span></a>
				</p>
			</dd>
			<dd class="clr-1">
				<p>
					<span>Мобильный:</span>
					<a class="link" onclick="goog_report_conversion('tel: 067-76-84-086');return false;" href="#">
						<span class="phone">(067)76-84-086</span>
					</a>
				</p>
			</dd>
			<dd>Или пишите:</dd>
			<dd class="clr-1">
				<p><span>E-mail:</span>
					<span>
						<script type="text/javascript">
							var cl = 'class="link e-mail"';
							var xv1 = 'ailt';
							var xv2 = 'hre';
							var xv3 = 'f="m';
							var xv4 = 'o:';
							var xv5 = 'bestfoto';
							var xv6 = 'i.ua';
							document.write('<a ' + cl + xv2 + xv3 + xv1 + xv4 + xv5 + '@' + xv6 + '">' + 'bestfoto' + '@' + xv6 + '</a>');
						</script>
					</span>
				</p>
			</dd>
		</dl>
		<div class="add-share">
			<a class="link-5" href="#" onclick="return add_favorite(this);">Добавить в Избранное</a>
		</div>
	</div>

	<div class="col-15">
		<div class="h-mod">
			<div class="bb-img-red">
				<h3 class="p2">Карта:</h3>
			</div>
		</div>
		<div>
			<img src="/images/map.png" alt="" class="img-border img-indent-2">
		</div>
	</div>

	<div class="col-16">
		<div class="h-mod">
			<div class="bb-img-red">
				<h3 class="p2">Жду Ваших писем:</h3>
			</div>
		</div>

		<form id="form" method="post" action="/about.php">
			<fieldset>
				<label><input type='text' name='nick' value='' required
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