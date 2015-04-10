<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<div class="center">
	<div class="form-comments">
<p class="m1">Редактирование сообщения #{ID}:</p>
<form id='form2' name='edit' action='{SCRIPT}?mode=edit&id={ID}' method='post'>
	<strong class="fleft">Автор:</strong>

	<label>
		<input style="position: relative; left: 10px;" type='text' name='nick' value='{POSTER}' required
						pattern="[?a-zA-Zа-яА-Я0-9_-\s]{2,20}" placeholder="Имя или Ник"
						title="Допустимы любые латинские и русские буквы, цифры, подчеркивание и тире.">
	</label>
	<div class="clear"></div>
	<strong class="fleft">E-mail:</strong>
	<label>
		<input style="position: relative; left: 10px;" type='email' name='email' value='{EMAIL}'
					 pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
					 placeholder="Не обязательно"
					 title="Допустимы любые латинские буквы, цифры и подчеркивание.">
	</label>
	<strong class="fleft">Сообщение:</strong>
	<label>
		<textarea style="width: 100%; margin-bottom: 10px;" name='mess' cols='90' rows='10' required
							title="Поле сообщений пустое!">{TEXT}</textarea>
	</label>
	<div class="clear"></div>
	<label class="fleft">Сейчас это сообщение <span style="color: red; "><b>{STATUS}</b></span>. Желаете его сделать
		<select name='flag'>
			<option value='1'>видимым</option>
			<option value='0'>скрытым</option>
		</select>
	</label>
	<label class="fright">
	<input type='submit' value='OK' name='submit'>
	</label>
</form>
		<div class="clear"></div>
<span class="s"> Также вы можете написать свой <a href='{SCRIPT}?mode=reply&id={ID}' target=_blank>ответ</a> на это сообщение или
	<a href='{SCRIPT}?mode=del&id={ID}' target=_blank>удалить</a> его из базы данных.</span>
		<div class="center"><span class="s">Желательно не использовать HTML-тэги, потому что они всё равно будут вырезаны при выводе сообщений в гостевую книгу.</span></div>
</div></div>
	</div>
</aside>