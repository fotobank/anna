<div class="pad-1 center">
	<div class="col-17">
		<p class="clr-1 center h5 p2">������ ������������ � ���� ������ - ��� ������ ���� ������ � ��������:</p>
<div class="form-comments center">
<form id="form2" action='{SCRIPT}' method='post' name='gb'>
	<fieldset>
<table class="w100">
<tr>
	<td class='b'>*���� ���:</td>
	<td class="w270">
		<label>
			<input  type='text' name='nick' value='{COOK_NICK}' required
											 pattern="[?a-zA-Z�-��-�0-9_-\s]{2,30}" placeholder="��� ��� ���"
											 title="��������� ����� ��������� � ������� �����, �����, ������������� � ����.">
		</label>
	</td>
	<td class="w270" rowspan='3'>	<h3>������ � ������������:</h3></td>
</tr>
	<tr id="bot">
		<td>�������:</td>
		<td>
			<label>
				<input type='text' name='name' value='cool'>
			</label>
		</td>
	</tr>
<tr>
	<td class='b'>E-mail:</td>
	<td>
		<label>
			<input type='email' name='email' value='{COOK_EMAIL}'
											pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
											placeholder="������������ �� �����"
											title="��������� ����� ��������� �����, ����� � �������������.">
		</label>
	</td>
</tr>
<tr>
	<td class='b td-text center' colspan='1'>*���������:</td>
	<td colspan='2' rowspan="2">
		<label>
			<textarea name='mess' cols='90' rows='6' required
								title="���� ��������� ������!"
								maxlength="300"></textarea>
		</label>
	</td>
</tr>
<tr>
	<td class="td-text center" colspan="1">���������� HTML-����:<br>
		&lt;b>, &lt;i> � &lt;u>
	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
		<div class="link-3 fright">{REFRESH_LINK} {ADMIN_LINK}</div>
	</td>
	<td>
		<label class="label-submit" >
			<input type='submit' name='submit' value='���������'>
		</label>
	</td>
</tr>
</table>
	</fieldset>
</form>
	</div>
		<div class="content-bottom">{FOTO_COMM}</div>
		<hr>
		<div class="form-comments center">
<table class="commentlist">
<tr>
	<td colspan='2' style='padding-top:10pt;padding-bottom:10pt;text-align: center;'>{PAGE_PREV} {PAGES} {PAGE_NEXT}</td>
</tr>
{MESSAGES}
	<tr>
		<td colspan='2' style='padding-top:10pt;padding-bottom:10pt;text-align: center;'>{PAGE_PREV} {PAGES} {PAGE_NEXT}</td>
	</tr>
</table>
	<div class="s-ver bb2">��������: <b>{PAGE_NOW}</b> �� {TOTAL_PAGES}<br>
		����� ���������: <b>{TOTAL_SHOW_MESS}</b></div>
</div>
	</div></div>