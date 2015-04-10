<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<div class="center">
	<div class="form-comments">
<form name="redactor" id="form" style="width: 100%;" action='{FORM}' method='post'>
<table class="w100 b0 sp3 pd1">
	<tr>
		<td colspan="2" style="height: 30px;">
			<p class='b'>Редактор модераторов</p>
		</td>
	</tr>
<tr style="vertical-align:middle;">
	<td style='padding-right: 20px;'>Введите имя учетной записи:</td>
	<td>
		<label>
			<input  type='text' name='login' value='{LOGIN}'
							pattern="[?a-zA-Zа-яА-Я0-9_-\s]{2,30}" placeholder="Имя или Ник"
							title="Допустимы любые латинские и русские буквы, цифры, подчеркивание и тире в колличестве от 2 до 20шт.">
		</label>

	</td>
</tr>
<tr style='vertical-align: middle;'>
	<td>Введите адрес e-mail:</td>
	<td>
		<label>
			<input type='email' name='email' value='{EMAIL}'
						 pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
						 placeholder="Обязательно"
						 title="Допустимы любые латинские буквы, цифры и подчеркивание.">
		</label>

	</td>
</tr>
<tr style='vertical-align: middle;'>
	<td style='padding-right: 20px;'>{PASS_TEXT}:</td>
	<td>
		<label>
			<input type="password" name='p_1' value="" id="password1"
						 pattern="[0-9a-zA-Z\_\-\!\~\*\:\<\>\+\.]{4,30}" placeholder="Pass"
						 title="Допустимы любые латинские буквы, цифры и знаки.">
		</label>
	</td>
</tr>
	<tr style='vertical-align: middle;'>
		<td style="text-align: left;">
			<label>
				<strong style="padding: 5px;">Генератор пароля:</strong>
				<a href="#" class="generatePassword">Сгенерировать</a>
			<select style="padding: 0 0 0 5px; margin: 0 10px 0 10px;" id="sel_opt" name="numbers">
				<option value="4" selected>4</option>
			</select>
				<a href="#" class="showPassword">Скрыть</a>
			</label>
		</td>
		<td>
			<label>
				<input type="password" name='p_2' value="" id="password2"
							 pattern="[0-9a-zA-Z\_\-\!\~\*\:\<\>\+\.]{4,30}" placeholder="Pass"
							 title="Допустимы любые латинские буквы, цифры и знаки.">
			</label>
		</td>
	</tr>
<tr>
	<td>
	</td>
	<td style='padding-bottom:10px;'>
		<input style="float: right;" type='submit' name='submit' value='OK'>
	</td>
</tr>
</table>
</form>
<span class='s'>{TEXT}</span>
</div></div>



<script type="text/javascript">
	$(document).ready(function() {
   //Скрипт генерации паролей
		var options = '<option value="4" selected>4</option>';
		for (j = 5; j <= 12; j++) {
			options += '<option value=' + j + '>' + j + '</option>';
		}
		$("#sel_opt").empty().append(options);

		function str_rand() {
			var result       = '';
			var words        = '!*+-_0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
			var max_position = words.length - 1;
			var n = document.redactor.numbers.value;
			for( i = 0; i < n; ++i ) {
				position = Math.floor ( Math.random() * max_position );
				result = result + words.substring(position, position + 1);
			}
			return result;
		}
		$('.showPassword').click(function(){
			var inputPsw = $('#password2');
			if (inputPsw.attr('type') == 'password') {
				document.getElementById('password1').setAttribute('type', 'text');
				document.getElementById('password2').setAttribute('type', 'text');
			} else {
				document.getElementById('password1').setAttribute('type', 'password');
				document.getElementById('password2').setAttribute('type', 'password');
			}
		});
		$('.generatePassword').click(function() {
			var pass = str_rand();
			document.getElementById('password1').setAttribute('type', 'text');
			document.getElementById('password2').setAttribute('type', 'text');
			$('#password1').attr('value', pass);
			$('#password2').attr('value', pass);
		});
	});

	window.onload = function () {
		document.getElementById("password1").onchange = validatePassword;
		document.getElementById("password2").onchange = validatePassword;
	};
	function validatePassword(){
		var pass2=document.getElementById("password2").value;
		var pass1=document.getElementById("password1").value;
		if(pass1!=pass2)
			document.getElementById("password2").setCustomValidity("Введенные пароли не совпадают!!!");
		else
			document.getElementById("password2").setCustomValidity('');
	}
</script>

	</div>
</aside>