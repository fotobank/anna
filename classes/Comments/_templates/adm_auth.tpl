<div class="center">
<p class="bb3 p2">Пожалуйста, введите свой логин и пароль, чтобы войти в административный раздел.</p>
<div class="form-admin">

<form id="form" name='auth' action='{SCRIPT}' method='post'>
	<fieldset>
		<label>
			<input type="text" name='login' value=""
						 pattern="[?a-zA-Zа-яА-Я0-9_-\s]{2,30}" placeholder="Login"
						 title="Допустимы любые латинские буквы, цифры, подчеркивание и тире.">
		</label>
		<label>
			<input type="password" name='pass' value=""
						 pattern="[0-9a-zA-Z\_\-\!\~\*\:\<\>\+\.]{4,20}" placeholder="Pass"
						 title="Допустимы любые латинские буквы, цифры и знаки.">
		</label>
<label>
	<input style="width: 100px; margin: 0 80px 0 0; float: right;" type='submit' name='submit' value='ok'>
</label>
	</fieldset>
</form>
</div>
</div>
<div class="clear"></div>
<div style="float: right;">
	<a href='/comments.php' style="padding-right: 20px;">...вернуться назад</a>
</div>