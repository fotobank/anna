<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<div class="center">
	<div class="form-comments">
<strong class="b h5 p2"> ����� ����� � ��������� �{ID}:</strong>
<table class="w100 b0 sp3 pd1">
<tr>
	<td style='height:30px;border:0;vertical-align: middle;'><b>�����:  {POSTER}</td>
	<td style='height:30px;border:0; vertical-align: middle;'><b>E-mail: <a href='mailto:{EMAIL}'>{EMAIL}</a></b></td>
</tr>
<tr>
	<td colspan='2'><b>���������:</b></td>
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
	<td><b>�����:</b></td>
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
����� �� ������ <a href='{SCRIPT}?mode=edit&id={ID}' target=_blank>���������������</a> ��� ��������� ���
		<a href='{SCRIPT}?mode=del&id={ID}' target=_blank>�������</a> ��� �� ���� ������.

	</div>
</div>
		<br><br>
	</div>
</aside>