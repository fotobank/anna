<table class='w100 b0 sp3 pd1 {TD_STYLE}'>
<tr>
	<td width='10%' rowspan='4' valign='middle' align='center'><b>#{ID}</b><br><br><input type='checkbox' class='check' name='ids[]' value='{ID}'></td>
	<td style='border:0' width='30%'>Автор: <b>{POSTER}</b></td>
	<td style='border:0' width='30%'>E-mail: <a href='mailto:{EMAIL}' class='{TD_STYLE}'">{EMAIL}</a></td>
	<td style='border:0'>Отправлено: {CREATE_TIME}</td>
</tr>
<tr>
	<td style='padding:10pt' colspan='3'>{TEXT}</td>
</tr>
<tr>
	<td style='border:0'>&nbsp;</td>
	<td colspan='2'>{REPLIES}</td>
</tr>
<tr>
	<td colspan='3' style='border:0' align='left'>IP: {IP}</td>
</tr>
</table>

<table width='30%' align='right'>
<tr>
	<td style='border:0'><a href='{SCRIPT}?mode=edit&id={ID}'>редактировать</a> | {SHOW_HIDE} | <a href='{SCRIPT}?mode=reply&id={ID}'>ответить</a> | <a href='{SCRIPT}?mode=del&id={ID}'>удалить</a></td>
</tr>
</table>
<a href='#'>наверх</a>
<br><br>