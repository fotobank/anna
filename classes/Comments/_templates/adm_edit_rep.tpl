<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<div class="center">
	<div class="form-comments">
<strong class="b h5 p2"> Пишем ответ к сообщению №{ID}:</strong>
<table class="w100 b0 sp3 pd1">
<tr>
	<td style='height:30px;border:0;vertical-align: middle;'><b>Автор:  {POSTER}</td>
	<td style='height:30px;border:0; vertical-align: middle;'><b>E-mail: <a href='mailto:{EMAIL}'>{EMAIL}</a></b></td>
</tr>
<tr>
	<td colspan='2'><b>Сообщение:</b></td>
</tr>
<tr>
	<td colspan='2'>
		<div style='width:700px;padding: 10pt;background: #2C2C2C;'>{TEXT}</div></td>
</tr>
</table>

<form id="form2" name='edit' action='{SCRIPT}?mode=reply&id={ID}' method='post'>
<table class="w100 b0 sp3 pd1">
<tr>
	<td style='height:30px;border:0; vertical-align: middle;'><b>by:</b>
		<label>
			<input type='text' name='rep_poster' value='{REP_POSTER}' disabled>
		</label>
	</td>
</tr>
<tr>
	<td><b>Ответ:</b></td>
</tr>
<tr>
	<td style='border:0'><label>
			<textarea style="width: 100%;padding: 10pt;" name='reply'>{REPLY}</textarea>
		</label></td>
</tr>
<tr>
	<td style='padding:10px;border:0; text-align: center;'><input type='hidden' name='rep_id' value='{REP_ID}'><input type='submit' name='submit' value='OK'></td>
</tr>
</table>
</form>
Также вы можете <a href='{SCRIPT}?mode=edit&id={ID}' target=_blank>отредактировать</a> это сообщение или
		<a href='{SCRIPT}?mode=del&id={ID}' target=_blank>удалить</a> его из базы данных.

	</div>
</div>
		<br><br>
	</div>
</aside>